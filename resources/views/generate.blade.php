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
            <th></th>
            <th>Button Dusk</th>
            <th>Button PHPUnit</th>
        </tr>
        <tr>
            <td>Generate</td>
            <form action="" method="post">
                {{ csrf_field() }}
                <td><input type="submit" value="dusk" name="btn" class="btn bg-success"></td>
                <td><input type="submit" value="phpunit" name="btn" class="btn bg-success"></td>
            </form>

        </tr>
    </table>

    <?php

    // use App\Anggota;

    // $model = new Anggota();
    // $dir = $model->getFillable();

    print_r(isset($dir) ? $dir : "");
    // foreach ($dir as $d) {
    //     if ("email" == $d) {
    //         print($d);
    //         echo "<br>";
    //     }
    // }
    ?>
</body>

</html>