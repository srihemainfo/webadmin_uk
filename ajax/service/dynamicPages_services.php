<?php
header('Content-Type: application/json; charset=utf-8');

include '../../include/shi-config.php';
include '../../include/functions.php';

if (isset($con)) {
    mysqli_set_charset($con, "utf8mb4");
}

error_reporting(E_ALL);
ini_set('display_errors', 0);

$action = $_POST['action'] ?? $_GET['action'] ?? '';

function uploadFileToS3($tmpPath, $mimeType, $originalName, $slug = 'page') {
    if (!file_exists($tmpPath)) return null;

    $ch = curl_init();
    $postData = [
        'image' => new CURLFile($tmpPath, $mimeType, $originalName),
        'name' => 'page-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $slug) . '-' . time() . '-' . rand(100, 999),
        'img_type' => 'page'
    ];

    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://mobapi.goride.run/api/s3-upload-image',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Authorization: Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()'
        ],
        CURLOPT_TIMEOUT => 45
    ]);

    $response = curl_exec($ch);
    if ($response === false) {
        curl_close($ch);
        return null;
    }
    curl_close($ch);

    $res = json_decode($response, true);
    if ($res && !empty($res['status']) && !empty($res['data']['url'])) {
        return $res['data']['url'];
    }
    return null;
}

try {
    // 1. LIST ALL PAGES
    if ($action === 'list') {
        $query = "SELECT id, page_title, slug, page_type, is_published, is_sitemap, updated_at, 
                         CASE WHEN sections IS NOT NULL AND sections != '' THEN 
                              JSON_LENGTH(sections) ELSE 0 END AS section_count
                  FROM dynamic_pages 
                  ORDER BY id DESC";
        $res = $con->query($query);
        $pages = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $pages[] = $row;
            }
        }
        echo json_encode(['status' => 'success', 'success' => true, 'data' => $pages]);
        exit;
    }

    // 2. GET SINGLE PAGE DATA
    if ($action === 'get') {
        $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Invalid Page ID']);
            exit;
        }

        $stmt = $con->prepare("SELECT id, page_title, slug, page_type, is_published, is_sitemap, seo_title, meta_description, meta_keywords, sections, created_at, updated_at FROM dynamic_pages WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $page = $result->fetch_assoc();

        if (!$page) {
            echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Page not found']);
            exit;
        }

        $page['sections'] = !empty($page['sections']) ? json_decode($page['sections'], true) : [];
        echo json_encode(['status' => 'success', 'success' => true, 'data' => $page]);
        exit;
    }

    // 3. UPLOAD SINGLE IMAGE
    if ($action === 'upload_image') {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['status' => 'error', 'success' => false, 'message' => 'No valid image file uploaded']);
            exit;
        }

        $slug = $_POST['slug'] ?? 'dynamic-page';
        $uploadedUrl = uploadFileToS3(
            $_FILES['image']['tmp_name'],
            $_FILES['image']['type'],
            $_FILES['image']['name'],
            $slug
        );

        if ($uploadedUrl) {
            echo json_encode(['status' => 'success', 'success' => true, 'url' => $uploadedUrl]);
        } else {
            echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Failed to upload image to S3']);
        }
        exit;
    }

    // 4. SAVE PAGE (CREATE OR UPDATE)
    if ($action === 'save') {
        $id = intval($_POST['id'] ?? 0);
        $page_title = trim($_POST['page_title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $page_type = trim($_POST['page_type'] ?? 'car-rental');
        $is_published = isset($_POST['is_published']) && ($_POST['is_published'] == '1' || $_POST['is_published'] === 'on') ? 1 : 0;
        $is_sitemap = isset($_POST['is_sitemap']) && ($_POST['is_sitemap'] == '1' || $_POST['is_sitemap'] === 'on') ? 1 : 0;
        $seo_title = trim($_POST['seo_title'] ?? '');
        $meta_description = trim($_POST['meta_description'] ?? '');
        $meta_keywords = trim($_POST['meta_keywords'] ?? '');

        if (empty($page_title)) {
            echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Page Title is required']);
            exit;
        }

        // Generate or sanitize slug
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $page_title), '-'));
        } else {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug), '-'));
        }

        // Validate slug uniqueness
        $slugCheckQuery = "SELECT id FROM dynamic_pages WHERE slug = ? AND id != ?";
        $slugStmt = $con->prepare($slugCheckQuery);
        $slugStmt->bind_param("si", $slug, $id);
        $slugStmt->execute();
        $slugRes = $slugStmt->get_result();
        if ($slugRes && $slugRes->num_rows > 0) {
            echo json_encode(['status' => 'error', 'success' => false, 'message' => 'The slug "' . $slug . '" is already in use by another page. Please choose a unique slug.']);
            exit;
        }

        // Sections JSON payload
        $sectionsRaw = $_POST['sections'] ?? '[]';
        $sections = is_string($sectionsRaw) ? json_decode($sectionsRaw, true) : $sectionsRaw;
        if (!is_array($sections)) {
            $sections = [];
        }

        $sectionsJson = json_encode($sections, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($id > 0) {
            // Update existing page
            $stmt = $con->prepare("UPDATE dynamic_pages SET 
                page_title = ?, 
                slug = ?, 
                page_type = ?, 
                is_published = ?, 
                is_sitemap = ?, 
                seo_title = ?, 
                meta_description = ?, 
                meta_keywords = ?, 
                sections = ? 
                WHERE id = ?");
            $stmt->bind_param(
                "sssiissssi",
                $page_title,
                $slug,
                $page_type,
                $is_published,
                $is_sitemap,
                $seo_title,
                $meta_description,
                $meta_keywords,
                $sectionsJson,
                $id
            );
            $stmt->execute();
            echo json_encode([
                'status' => 'success', 'success' => true,
                'message' => 'Page updated successfully!',
                'id' => $id,
                'slug' => $slug
            ]);
            exit;
        } else {
            // Insert new page
            $nameVal = $page_title;
            $stmt = $con->prepare("INSERT INTO dynamic_pages (
                page_title, 
                name,
                slug, 
                page_type, 
                is_published, 
                is_sitemap, 
                seo_title, 
                meta_description, 
                meta_keywords, 
                sections
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "ssssiissss",
                $page_title,
                $nameVal,
                $slug,
                $page_type,
                $is_published,
                $is_sitemap,
                $seo_title,
                $meta_description,
                $meta_keywords,
                $sectionsJson
            );
            $stmt->execute();
            $newId = $stmt->insert_id;
            echo json_encode([
                'status' => 'success', 'success' => true,
                'message' => 'Page created successfully!',
                'id' => $newId,
                'slug' => $slug
            ]);
            exit;
        }
    }

    // 5. DELETE PAGE
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Invalid Page ID']);
            exit;
        }

        $stmt = $con->prepare("DELETE FROM dynamic_pages WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Page deleted successfully']);
        exit;
    }

    // 6. TOGGLE STATUS
    if ($action === 'toggle_status') {
        $id = intval($_POST['id'] ?? 0);
        $status = isset($_POST['status']) ? intval($_POST['status']) : intval($_POST['is_published'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Invalid Page ID']);
            exit;
        }

        $stmt = $con->prepare("UPDATE dynamic_pages SET is_published = ? WHERE id = ?");
        $stmt->bind_param("ii", $status, $id);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Status updated successfully']);
        exit;
    }

    echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Invalid action request']);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'success' => false, 'message' => $e->getMessage()]);
}
