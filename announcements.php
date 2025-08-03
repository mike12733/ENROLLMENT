<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$success = '';
$error = '';

// Handle new announcement
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_announcement'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (empty($title) || empty($content)) {
        $error = 'Title and content are required.';
    } else {
        if (createAnnouncement($title, $content, $_SESSION['user_id'])) {
            $success = 'Announcement created successfully!';
        } else {
            $error = 'Failed to create announcement.';
        }
    }
}

// Handle edit announcement
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_announcement'])) {
    $id = $_POST['announcement_id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (empty($title) || empty($content)) {
        $error = 'Title and content are required.';
    } else {
        if (updateAnnouncement($id, $title, $content)) {
            $success = 'Announcement updated successfully!';
        } else {
            $error = 'Failed to update announcement.';
        }
    }
}

// Handle delete announcement
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_announcement'])) {
    $id = $_POST['announcement_id'];
    
    if (deleteAnnouncement($id)) {
        $success = 'Announcement deleted successfully!';
    } else {
        $error = 'Failed to delete announcement.';
    }
}

$announcements = getAllAnnouncements();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Online Enrollment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap"></i> Online Enrollment System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="students.php">Manage Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="announcements.php">Announcements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports.php">Reports</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-bullhorn"></i> Announcements</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAnnouncementModal">
                        <i class="fas fa-plus"></i> New Announcement
                    </button>
                </div>
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="row">
                    <?php foreach ($announcements as $announcement): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($announcement['title']); ?></h5>
                                    <small class="text-muted"><?php echo date('M d, Y', strtotime($announcement['created_at'])); ?></small>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                                    <small class="text-muted">Posted by: <?php echo $announcement['username']; ?></small>
                                </div>
                                <div class="card-footer">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="editAnnouncement(<?php echo $announcement['id']; ?>, '<?php echo addslashes($announcement['title']); ?>', '<?php echo addslashes($announcement['content']); ?>')">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteAnnouncement(<?php echo $announcement['id']; ?>, '<?php echo addslashes($announcement['title']); ?>')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (empty($announcements)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No announcements yet</h5>
                        <p class="text-muted">Create your first announcement to get started.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- New Announcement Modal -->
    <div class="modal fade" id="newAnnouncementModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content *</label>
                            <textarea class="form-control" id="content" name="content" rows="6" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="create_announcement" class="btn btn-primary">Create Announcement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Announcement Modal -->
    <div class="modal fade" id="editAnnouncementModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="edit_announcement_id" name="announcement_id">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_content" class="form-label">Content *</label>
                            <textarea class="form-control" id="edit_content" name="content" rows="6" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="edit_announcement" class="btn btn-primary">Update Announcement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Announcement Modal -->
    <div class="modal fade" id="deleteAnnouncementModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this announcement?</p>
                    <p><strong>Title:</strong> <span id="delete_announcement_title"></span></p>
                </div>
                <form method="POST">
                    <div class="modal-footer">
                        <input type="hidden" id="delete_announcement_id" name="announcement_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_announcement" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        function editAnnouncement(id, title, content) {
            document.getElementById('edit_announcement_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_content').value = content;
            
            const modal = new bootstrap.Modal(document.getElementById('editAnnouncementModal'));
            modal.show();
        }

        function deleteAnnouncement(id, title) {
            document.getElementById('delete_announcement_id').value = id;
            document.getElementById('delete_announcement_title').textContent = title;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteAnnouncementModal'));
            modal.show();
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>