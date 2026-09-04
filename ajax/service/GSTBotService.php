<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../include/shi-config.php';
include '../../include/functions.php';

require __DIR__ . '/../../vendor/autoload.php';


use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;


$headers = apache_request_headers();
$post_csrf = $headers['X-Csrf-Token'] ?? '';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

if($method == 'gst_details'){
    $result = [];
    
    
    $id = BlockSQLInjection($_POST['id']);
    $contype = " `id` = $id AND `type` = 'Owner' AND `o_proof_type` = 'gst'";
    
    $Suspended = select_query($con, "kyc_details", "", " $contype  AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
    // var_dump($Suspended);die;

    if ($Suspended['nr'] > 0) {

        $details = $Suspended['result']['0']['gst_details'];
        
        if($details && $details != ''){
            
            $result['type'] = '1';
            $result['result'] = $details;
            
            $get_sql = "
                SELECT 
                    cu.name,
                    cu.mobile,
                    cu.doc_verify,
                    cu.vehicle_verify,
                    kd.*
                FROM kyc_details kd
                JOIN user_register cu ON kd.user_id = cu.id
                WHERE kd.id = $id
                  AND cu.deletes = '0'
                LIMIT 1
            ";
            
            $get_res = mysqli_query($con, $get_sql);
            $complain = mysqli_fetch_assoc($get_res);
            
            $result['data'] = $complain;
            
            echo json_encode($result);
            
        }else{
            
            // -------------------------------
            // 🔧 Remote Selenium / Browserless URL
            // -------------------------------
            // $seleniumServerUrl = 'https://2TBvhKiceu3ejw582a625ef796385283b0de5017344071e0d@chrome.browserless.io/webdriver'; 
            
            $BROWSERLESS_KEY = BROWSER_LESS_KEY;
            $seleniumServerUrl = "https://{$BROWSERLESS_KEY}@chrome.browserless.io/webdriver";
            
            // Replace YOUR_API_KEY with your Browserless or ChromeDriver key
            
            // -------------------------------
            // ⚙️ Chrome optimization flags
            // -------------------------------
            $options = new ChromeOptions();
            $options->addArguments([
                '--headless=new', // newer faster headless mode
                '--disable-gpu',
                '--no-sandbox',
                '--disable-dev-shm-usage',
                '--blink-settings=imagesEnabled=false', // disable images
                '--disable-extensions',
                '--disable-infobars',
                '--disable-notifications',
                '--disable-popup-blocking',
                '--disable-translate',
                '--window-size=1366,768',
            ]);
            
            $capabilities = DesiredCapabilities::chrome();
            $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
            
            // -------------------------------
            // 🚀 Connect to Remote WebDriver
            // -------------------------------
            $driver = RemoteWebDriver::create($seleniumServerUrl, $capabilities, 30000, 30000);
            
            try {
                // -------------------------------
                // 🌐 Open GST search page
                // -------------------------------
                $driver->get('https://cleartax.in/gst-number-search');
            
                // Wait for the input to load instead of sleep()
                $wait = new WebDriverWait($driver, 10);
                $wait->until(
                    WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath("//input[@id='input']"))
                );
            
                // -------------------------------
                // ✍️ Search GST number
                // -------------------------------
                $gstNumber = $Suspended['result']['0']['o_proof_no'];
            
                $input = $driver->findElement(WebDriverBy::xpath("//input[@id='input']"));
                $input->clear();
                $input->sendKeys($gstNumber);
            
                $driver->findElement(WebDriverBy::xpath("//button[contains(.,'SEARCH')]"))->click();
            
                // Wait for result to appear
                $wait->until(
                    WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath("//div[contains(@class,'w-1/3') and .//small]"))
                );
            
                // -------------------------------
                // 📊 Extract business info
                // -------------------------------
                $businessDivs = $driver->findElements(WebDriverBy::xpath("//div[contains(@class,'w-1/3') and .//small]"));
                $data = [];
            
                foreach ($businessDivs as $div) {
                    $header = $div->findElement(WebDriverBy::tagName('h4'))->getText();
                    $value  = $div->findElement(WebDriverBy::tagName('small'))->getText();
                    $data[$header] = $value;
                }
                
                $col = 'gst_details';
                $new_status = json_encode($data);
                
                $update_sql = "UPDATE kyc_details SET $col = ? WHERE id = ?";
                $update_stmt = $con->prepare($update_sql);
                $update_stmt->bind_param("si", $new_status, $id);
                $update_stmt->execute();
                
                $result['type'] = '1';
                $result['result'] = $new_status;
                
                $get_sql = "
                    SELECT 
                        cu.name,
                        cu.mobile,
                        cu.doc_verify,
                        cu.vehicle_verify,
                        kd.*
                    FROM kyc_details kd
                    JOIN user_register cu ON kd.user_id = cu.id
                    WHERE kd.id = $id
                      AND cu.deletes = '0'
                    LIMIT 1
                ";
                
                $get_res = mysqli_query($con, $get_sql);
                $complain = mysqli_fetch_assoc($get_res);
                
                $result['data'] = $complain;
                
                echo json_encode($result);
            
            } catch (Exception $e) {
                $result['type'] = '0';
                $result['result'] = 'GST not Valid';
                
                echo json_encode($result);
                // echo json_encode(['error' => $e->getMessage()]);
            } finally {
                $driver->quit();
            }
        }
        
    } else {
        $result['type'] = '0';
        $result['result'] = 'Record Not found';
        
        echo json_encode($result);
    }
}