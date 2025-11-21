<?php
// DATABASE CONNECTION
$conn = new mysqli("localhost", "root", "", "khush");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// -----------------------------------------
// INSERT USER
// -----------------------------------------
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $conn->query("INSERT INTO users(name,email) VALUES('$name','$email')");
    echo "<script>alert('User Added');</script>";
}

// -----------------------------------------
// DELETE USER
// -----------------------------------------
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
    echo "<script>alert('User Deleted');</script>";
}

// -----------------------------------------
// UPDATE USER
// -----------------------------------------
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $conn->query("UPDATE users SET name='$name', email='$email' WHERE id=$id");
    echo "<script>alert('User Updated');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD SYSTEM</title>

    <!-- BOOTSTRAP CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fa;
        }
        .container-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .operation-box {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0px 3px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="container-box">

        <h2><span style="color:#007bff;">CRUD Operations</span> — Modern UI</h2>

        <!-- MENU -->
        <form method="get" class="mb-4">
            <label class="form-label fw-bold">Select Operation:</label>
            <select name="action" class="form-select" onchange="this.form.submit()">
                <option value="">-- Choose --</option>
                <option value="insert">Insert User</option>
                <option value="view">View All Users</option>
                <option value="update">Update User</option>
                <option value="delete">Delete User</option>
            </select>
        </form>

        <hr>

        <?php
        // --------------------- INSERT FORM ---------------------
        if (isset($_GET['action']) && $_GET['action'] == "insert") {
        ?>
            <h3 class="text-primary">Add User</h3>
            <form method="post" class="mt-3">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input class="form-control" type="text" name="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" required>
                </div>
                <button type="submit" name="add" class="btn btn-success">Add User</button>
            </form>
        <?php
        }

        // --------------------- VIEW USERS ---------------------
        if (isset($_GET['action']) && $_GET['action'] == "view") {
            $result = $conn->query("SELECT * FROM users");

            echo "<h3 class='text-primary mb-3'>All Users</h3>";
            echo "<table class='table table-bordered table-striped table-hover'>
                    <thead class='table-dark'>
                        <tr><th>ID</th><th>Name</th><th>Email</th></tr>
                    </thead><tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                     </tr>";
            }
            echo "</tbody></table>";
        }

        // --------------------- UPDATE LIST ---------------------
        if (isset($_GET['action']) && $_GET['action'] == "update" && !isset($_GET['edit'])) {
            $result = $conn->query("SELECT * FROM users");

            echo "<h3 class='text-primary'>Select User to Update</h3><ul class='list-group mt-3'>";
            while ($row = $result->fetch_assoc()) {
                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                        {$row['id']} - {$row['name']}
                        <a class='btn btn-warning btn-sm' href='CURD.php?action=update&edit={$row['id']}'>Edit</a>
                      </li>";
            }
            echo "</ul>";
        }

        // --------------------- UPDATE FORM ---------------------
        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            $res = $conn->query("SELECT * FROM users WHERE id=$id");
            $data = $res->fetch_assoc();
        ?>
            <h3 class="text-primary">Edit User</h3>
            <form method="post" class="mt-3">
                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input class="form-control" type="text" name="name" value="<?= $data['name'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" value="<?= $data['email'] ?>" required>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Update</button>
            </form>
        <?php
        }

        // --------------------- DELETE LIST ---------------------
        if (isset($_GET['action']) && $_GET['action'] == "delete") {
            $result = $conn->query("SELECT * FROM users");

            echo "<h3 class='text-danger'>Select User to Delete</h3><ul class='list-group mt-3'>";
            while ($row = $result->fetch_assoc()) {
                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                        {$row['id']} - {$row['name']}
                        <a class='btn btn-danger btn-sm' href='CURD.php?delete={$row['id']}'>Delete</a>
                      </li>";
            }
            echo "</ul>";
        }
        ?>
    </div>
</div>

</body>
</html>
