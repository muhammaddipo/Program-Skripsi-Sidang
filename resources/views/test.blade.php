<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Code</title>

    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <th>Feature</th>
            <th>Button Dusk</th>
            <th>Button PHPUnit</th>
        </tr>
        <tr>
            <td>Login</td>
            <form action="" method="post">
                {{ csrf_field() }}
                <td><input type="submit" value="dusk" name="btn" class="btn bg-success"></td>
                <td><input type="submit" value="phpunit" name="btn" class="btn bg-success"></td>
                <!-- <td><button>BTN 1</button></td>
                <td><button>BTN 2</button></td> -->
            </form>

        </tr>
    </table>
</body>

</html>

<?php
print_r(isset($dir) ? $dir : "");
?>