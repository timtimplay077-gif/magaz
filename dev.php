<?php
include('data/database.php');
include('data/category.php');
$arr_trans = [
    " " => "-",
    "," => "",
    "-" => "-",
    "а" => "a",
    "б" => "b",
    "в" => "v",
    "г" => "h",
    "ґ" => "g",
    "д" => "d",
    "е" => "e",
    "є" => "ye",
    "ж" => "zh",
    "з" => "z",
    "и" => "y",
    "і" => "i",
    "ї" => "yi",
    "й" => "y",
    "к" => "k",
    "л" => "l",
    "м" => "m",
    "н" => "n",
    "о" => "o",
    "п" => "p",
    "р" => "r",
    "с" => "s",
    "т" => "t",
    "у" => "y",
    "ф" => "f",
    "х" => "kh",
    "ц" => "ts",
    "ч" => "ch",
    "ш" => "sh",
    "щ" => "shch",
    "ь" => "",
    "ю" => "yu",
    "я" => "ya"
];
function trans($str)
{
    global $arr_trans;
    $new_str = "";
    for ($i = 0; $i < mb_strlen($str); $i++) {
        $new_str .= $arr_trans[mb_strtolower(mb_substr($str, $i, 1))];
    }
    return $new_str;
}
for ($i = 0; $i < count($category); $i++) {
    $category_name = $category[$i]["name"];
    $category_link = trans($category[$i]["name"]);
    $category_sql = "INSERT INTO `categories` (`id`, `name`, `link`) VALUES (NULL, '$category_name', '$category_link')";
    $category_query = $db_conn->query($category_sql);
}
?>