<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include '../../include/shi-config.php';
include '../../include/functions.php';

require __DIR__ . '/../../vendor/autoload.php';

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;

function apiResponse($type, $msg, $data = [])
{
    echo json_encode([
        "type"   => $type,
        "result" => $msg,
        "data"   => $data
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}

$method = $_REQUEST["method"] ?? "";

if ($method !== "toll_details") {
    apiResponse(0, "Invalid Method");
}

if ($_POST['authKey'] != "ASDFGHJKLqwertyuiopMNBVCXZ!@#$%^&*()0987612345") {
    apiResponse(0, "Invalid Auth Token");
}

try {

    $from_place = BlockSQLInjection($_POST['from_place'] ?? '');
    $to_place   = BlockSQLInjection($_POST['to_place'] ?? '');

    if (empty($from_place) || empty($to_place)) {
        apiResponse(0, "From & To places are required");
    }

    // =======================
    // CHECK EXISTING RECORD
    // =======================
    $query = "SELECT * FROM toll_fare WHERE from_place = ? AND to_place = ? AND deletes = 0 ORDER BY id DESC LIMIT 1";
    $stmt  = $con->prepare($query);
    $stmt->bind_param("ss", $from_place, $to_place);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($record = $result->fetch_assoc()) {
        if (!empty($record['req_response'])) {
            apiResponse(1, json_decode($record['req_response'], true), $record);
        }
    }

    // =======================
    // SETUP SELENIUM
    // =======================
    $BROWSERLESS_KEY = BROWSER_LESS_KEY;
    $remoteWebDriverUrl = "https://{$BROWSERLESS_KEY}@chrome.browserless.io/webdriver";

    $options = new ChromeOptions();
    $options->addArguments([
        '--headless=new',
        '--disable-gpu',
        '--no-sandbox',
        '--disable-dev-shm-usage',
        '--blink-settings=imagesEnabled=false',
        '--disable-extensions',
        '--disable-infobars',
        '--disable-notifications',
        '--disable-popup-blocking',
        '--window-size=2560,1440',
    ]);

    $caps = DesiredCapabilities::chrome();
    $caps->setCapability(ChromeOptions::CAPABILITY, $options);

    $driver = RemoteWebDriver::create($remoteWebDriverUrl, $caps, 60000, 60000);
    $wait   = new WebDriverWait($driver, 10);

    $driver->get("https://tollguru.com/toll-calculator-india");
    sleep(2); // NO ZOOM!

    $fromBox = $wait->until(
        WebDriverExpectedCondition::elementToBeClickable(
            WebDriverBy::xpath("(//input[@role='combobox'])[1]")
        )
    );

    // JS Click instead of Selenium click
    $driver->executeScript("arguments[0].click();", [$fromBox]);
    $fromBox->sendKeys($from_place);

    $wait->until(
        WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
            WebDriverBy::xpath("//ul[@role='listbox']//li[@role='option']")
        )
    )[0]->click();

    // =======================
    // TO PLACE
    // =======================
    $toBox = $wait->until(
        WebDriverExpectedCondition::elementToBeClickable(
            WebDriverBy::xpath("(//input[@role='combobox'])[2]")
        )
    );
    $driver->executeScript("arguments[0].click();", [$toBox]);
    $toBox->sendKeys($to_place);
    sleep(1);

    $wait->until(
        WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
            WebDriverBy::xpath("//ul[@role='listbox']//li[@role='option']")
        )
    )[0]->click();

    // =======================
    // SUBMIT BUTTON CLICK (JS CLICK)
    // =======================
    $submitBtn = $wait->until(
        WebDriverExpectedCondition::elementToBeClickable(
            WebDriverBy::xpath("(//span[@class='flex justify-center items-center gap-2 text-black'])[1]")
        )
    );

    // Prevent click interception by using JS click
    $driver->executeScript("arguments[0].click();", [$submitBtn]);

    sleep(4);

    // =======================
    // SCRAPE RESULTS
    // =======================
    $toll = trim($wait->until(
        WebDriverExpectedCondition::presenceOfElementLocated(
            WebDriverBy::xpath("//div[@role='tooltip']")
        )
    )->getText());

    $fuel = trim($wait->until(
        WebDriverExpectedCondition::presenceOfElementLocated(
            WebDriverBy::xpath("//td[contains(@aria-label,'Fuel')]")
        )
    )->getText());

    $total = trim($wait->until(
        WebDriverExpectedCondition::presenceOfElementLocated(
            WebDriverBy::xpath("//td[contains(@aria-label,'Total')]")
        )
    )->getText());

    $driver->quit();

    // =======================
    // SAVE IN DATABASE
    // =======================
    $data = [
        "toll"  => trim(str_replace("₹", "", $toll)),
        "fuel"  => trim(str_replace("₹", "", $fuel)),
        "total" => trim(str_replace("₹", "", $total)),
    ];

    $json = json_encode($data, JSON_UNESCAPED_UNICODE);

    $insert = $con->prepare(
        "INSERT INTO toll_fare (from_place, to_place, fare, req_response, created_at, updated_at)
         VALUES (?, ?, ?, ?, NOW(), NOW())"
    );
    $insert->bind_param("ssss", $from_place, $to_place, trim(str_replace("₹", "", $toll)), $json);
    $insert->execute();

    $id = $insert->insert_id;

    $fetch = $con->prepare("SELECT * FROM toll_fare WHERE id = ?");
    $fetch->bind_param("i", $id);
    $fetch->execute();
    $record = $fetch->get_result()->fetch_assoc();

    apiResponse(1, $data, $record);

} catch (Exception $e) {

    if (isset($driver)) {
        try { $driver->quit(); } catch (Exception $ex) {}
    }

    apiResponse(
        0,
        "Error on line " . $e->getLine() . " in " . basename($e->getFile()) . ": " . $e->getMessage()
    );
}