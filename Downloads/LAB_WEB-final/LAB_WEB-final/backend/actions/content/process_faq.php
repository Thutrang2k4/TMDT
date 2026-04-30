<?php
/**
 * Xử lý các thao tác với FAQ (Thêm, Sửa, Xóa, Toggle Status)
 * Backend Action - Nhiệm vụ #2
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../../auth/check_role.php';
require_once __DIR__ . '/../../models/content_model.php';

// Kiểm tra quyền admin
requireAdmin();

// Hàm validate FAQ
function validateFAQ($question, $answer, $category)
{
    $errors = array();

    if (empty($question)) {
        $errors[] = "Câu hỏi không được để trống";
    } elseif (strlen($question) > 500) {
        $errors[] = "Câu hỏi không được vượt quá 500 ký tự";
    }

    if (empty($answer)) {
        $errors[] = "Câu trả lời không được để trống";
    }

    if (empty($category)) {
        $errors[] = "Danh mục không được để trống";
    } elseif (strlen($category) > 100) {
        $errors[] = "Danh mục không được vượt quá 100 ký tự";
    }

    return $errors;
}

// Xử lý request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add':
            // Thêm FAQ mới
            $question = trim($_POST['question'] ?? '');
            $answer = trim($_POST['answer'] ?? '');
            $category = trim($_POST['category'] ?? 'Chung');
            $display_order = intval($_POST['display_order'] ?? 0);
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            $errors = validateFAQ($question, $answer, $category);

            if (empty($errors)) {
                $user_id = $_SESSION['user_id'] ?? 0;
                if (addFAQ($question, $answer, $category, $display_order, $is_active, $user_id)) {
                    header("Location: ../../../frontend/admin/manage_faq.php?success=added");
                    exit();
                } else {
                    $errors[] = "Có lỗi xảy ra khi thêm FAQ";
                }
            }

            $_SESSION['faq_errors'] = $errors;
            $_SESSION['faq_form_data'] = $_POST;
            header("Location: ../../../frontend/admin/manage_faq.php?error=validation");
            exit();
            break;

        case 'edit':
            // Sửa FAQ
            $id = intval($_POST['id'] ?? 0);
            $question = trim($_POST['question'] ?? '');
            $answer = trim($_POST['answer'] ?? '');
            $category = trim($_POST['category'] ?? 'Chung');
            $display_order = intval($_POST['display_order'] ?? 0);
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if ($id <= 0) {
                header("Location: ../../../frontend/admin/manage_faq.php?error=invalid_id");
                exit();
            }

            $errors = validateFAQ($question, $answer, $category);

            if (empty($errors)) {
                if (updateFAQ($id, $question, $answer, $category, $display_order, $is_active)) {
                    header("Location: ../../../frontend/admin/manage_faq.php?success=updated");
                    exit();
                } else {
                    $errors[] = "Có lỗi xảy ra khi cập nhật FAQ";
                }
            }

            $_SESSION['faq_errors'] = $errors;
            $_SESSION['faq_form_data'] = $_POST;
            header("Location: ../../../frontend/admin/manage_faq.php?id=$id&error=validation");
            exit();
            break;

        case 'delete':
            // Xóa FAQ
            $id = intval($_POST['id'] ?? 0);

            if ($id <= 0) {
                header("Location: ../../../frontend/admin/manage_faq.php?error=invalid_id");
                exit();
            }

            if (deleteFAQ($id)) {
                header("Location: ../../../frontend/admin/manage_faq.php?success=deleted");
            } else {
                header("Location: ../../../frontend/admin/manage_faq.php?error=delete_failed");
            }
            exit();
            break;

        case 'toggle_status':
            // Toggle trạng thái active/inactive
            $id = intval($_POST['id'] ?? 0);

            if ($id <= 0) {
                header("Location: ../../../frontend/admin/manage_faq.php?error=invalid_id");
                exit();
            }

            if (toggleFAQStatus($id)) {
                header("Location: ../../../frontend/admin/manage_faq.php?success=status_updated");
            } else {
                header("Location: ../../../frontend/admin/manage_faq.php?error=status_failed");
            }
            exit();
            break;

        default:
            header("Location: ../../../frontend/admin/manage_faq.php?error=invalid_action");
            exit();
    }
}

// GET request - xử lý xóa qua URL
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'delete' && isset($_GET['id'])) {
        $id = intval($_GET['id']);

        if ($id > 0 && deleteFAQ($id)) {
            header("Location: ../../../frontend/admin/manage_faq.php?success=deleted");
        } else {
            header("Location: ../../../frontend/admin/manage_faq.php?error=delete_failed");
        }
        exit();
    }

    if ($action === 'toggle' && isset($_GET['id'])) {
        $id = intval($_GET['id']);

        if ($id > 0 && toggleFAQStatus($id)) {
            header("Location: ../../../frontend/admin/manage_faq.php?success=status_updated");
        } else {
            header("Location: ../../../frontend/admin/manage_faq.php?error=status_failed");
        }
        exit();
    }
}

// Nếu không có action hợp lệ
header("Location: ../../../frontend/admin/manage_faq.php");
exit();

