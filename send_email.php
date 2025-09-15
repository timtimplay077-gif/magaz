<?php
include('data/session_start.php');
include('data/database.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (!isset($_SESSION['order_data'])) {
  die("Данные заказа не найдены. Вернитесь к оформлению заказа.");
}

$order_data = $_SESSION['order_data'];
$firstName = $order_data['firstName'] ?? '';
$lastName = $order_data['lastName'] ?? '';
$email = $order_data['email'] ?? '';
$phone = $order_data['phone'] ?? '';
$city = $order_data['city'] ?? '';
$region = $order_data['region'] ?? '';
$adres = $order_data['adres'] ?? '';
$nova_poshta = $order_data['nova_poshta'] ?? '';
$basket_items = $order_data['basket_items'] ?? [];
$total_amount = $order_data['total_amount'] ?? 0;
$user_sale = $order_data['user_sale'] ?? 0;

$toEmail = 'admin@kanskrop.com';

if (empty($firstName) || empty($lastName) || empty($email) || empty($phone)) {
  die("Заполните обязательные поля: имя, фамилия, email, телефон");
}

$message = file_get_contents("mail/rekvisit.php");
if ($message === false) {
  die("Не удалось загрузить шаблон письма");
}

$message = str_replace('{{first_name}}', htmlspecialchars($firstName), $message);
$message = str_replace('{{last_name}}', htmlspecialchars($lastName), $message);
$message = str_replace('{{email}}', htmlspecialchars($email), $message);
$message = str_replace('{{email_raw}}', htmlspecialchars($email), $message);
$message = str_replace('{{phone}}', htmlspecialchars($phone), $message);
$message = str_replace('{{phone_raw}}', htmlspecialchars(preg_replace('/[^0-9+]/', '', $phone)), $message);
$message = str_replace('{{city}}', htmlspecialchars($city), $message);
$message = str_replace('{{region}}', htmlspecialchars($region), $message);
$message = str_replace('{{address}}', htmlspecialchars($adres), $message);
$message = str_replace('{{nova_poshta}}', !empty($nova_poshta) ? 'Нова Пошта: ' . htmlspecialchars($nova_poshta) : '', $message);

$products_html = '
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f4f6f8;padding:20px 0;font-family:Arial, sans-serif;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,0.08);margin-top:20px;">
        <tr>
          <td style="padding:20px 24px;border-bottom:1px solid #eef0f2;">
            <h2 style="margin:0;font-size:20px;color:#0f1724;">Деталі замовлення</h2>';
if (!empty($user_sale)) {
  $products_html .= '<p style="margin:6px 0 0;font-size:13px;color:#667085;">Інформація про товари у замовленні (зі знижкою ' . $user_sale . '%)</p>';
} else {
  $products_html .= '<p style="margin:6px 0 0;font-size:13px;color:#667085;">Інформація про товари у замовленні</p>';
}

$products_html .= '
          </td>
        </tr>
        <tr>
          <td style="padding:18px 24px;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size:14px;color:#101828;">
              <tr style="background-color:#f8f9fa;">
                <td style="padding:12px;border-bottom:2px solid #e9ecef;font-weight:bold;">Код</td>
                <td style="padding:12px;border-bottom:2px solid #e9ecef;font-weight:bold;">Товар</td>
                <td style="padding:12px;border-bottom:2px solid #e9ecef;font-weight:bold;text-align:center;">Кількість</td>
                <td style="padding:12px;border-bottom:2px solid #e9ecef;font-weight:bold;text-align:right;">Ціна</td>
                <td style="padding:12px;border-bottom:2px solid #e9ecef;font-weight:bold;text-align:right;">Сума</td>
              </tr>';

if (!empty($basket_items)) {
  foreach ($basket_items as $item) {
    $item_total = $item['final_price'] * $item['count'];
    $product_code = $item['productCode'] ?? 'н/д';

    $products_html .= '
              <tr>
                <td style="padding:12px;border-bottom:1px solid #eef0f2;font-weight:bold;color:#0b66ff;">' . htmlspecialchars($product_code) . '</td>
                <td style="padding:12px;border-bottom:1px solid #eef0f2;">' . htmlspecialchars($item['name']);

    if (!empty($item['price_modifier'])) {
      $modifier_type = $item['price_modifier'] > 0 ? "надбавка" : "знижка";
      $products_html .= '<br><small style="color:#667085;">(' . $modifier_type . ': ' . abs($item['price_modifier']) . '%)</small>';
    }

    $products_html .= '</td>
                <td style="padding:12px;border-bottom:1px solid #eef0f2;text-align:center;">' . $item['count'] . ' шт.</td>
                <td style="padding:12px;border-bottom:1px solid #eef0f2;text-align:right;">' . number_format($item['final_price'], 2) . ' ₴</td>
                <td style="padding:12px;border-bottom:1px solid #eef0f2;text-align:right;font-weight:bold;">' . number_format($item_total, 2) . ' ₴</td>
              </tr>';
  }
} else {
  $products_html .= '
              <tr>
                <td colspan="5" style="padding:12px;text-align:center;color:#667085;">Немає даних про товари</td>
              </tr>';
}

$products_html .= '
              <tr>
                <td colspan="4" style="padding:12px;text-align:right;font-weight:bold;border-top:2px solid #e9ecef;">Загальна сума:</td>
                <td style="padding:12px;text-align:right;font-weight:bold;border-top:2px solid #e9ecef;color:#0b66ff;">' . number_format($total_amount, 2) . ' ₴</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding:14px 24px;background:#fafafa;border-top:1px solid #eef0f2;">
            <p style="margin:0;font-size:12px;color:#98a2b3;">Це авто-згенерований лист. Будь ласка, не відповідайте на нього.</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>';
$message = str_replace('</body>', $products_html . '</body>', $message);

$data = [
  ["Код товара", "Наименование товара", "Количество", "Цена за шт. (со скидкой)", "Итого", "Адрес доставки", "Новая Почта"],
];

foreach ($basket_items as $item) {
  $item_total = $item['final_price'] * $item['count'];
  $product_code = $item['productCode'] ?? 'н/д';

  $data[] = [
    $product_code,
    $item['name'],
    $item['count'],
    $item['final_price'],
    $item_total,
    "$city, $region, $address",
    $nova_poshta
  ];
}
$data[] = ["", "", "", "Общая сумма:", $total_amount, "", ""];

if (!file_exists('cards')) {
  mkdir('cards', 0777, true);
}

$f_name = "cards/card" . time() . ".csv";
$fp = fopen($f_name, "w");

fwrite($fp, "\xEF\xBB\xBF");

foreach ($data as $value) {
  fputcsv($fp, $value, ';');
}
fclose($fp);

try {
  $mail = new PHPMailer(true);

  $mail->isSMTP();
  $mail->Host = 'smtp.hostinger.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'admin@kanskrop.com';
  $mail->Password = 'Adminkanskrop2025!';
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;

  $mail->setFrom('admin@kanskrop.com', 'Kanskrop Shop');
  $mail->addAddress($toEmail, 'Admin');
  $mail->addReplyTo($email, $firstName . ' ' . $lastName);

  $mail->CharSet = 'UTF-8';
  $mail->Encoding = 'base64';
  $mail->isHTML(true);

  $mail->Subject = 'Новый заказ от ' . $firstName . ' ' . $lastName;
  $mail->Body = $message;
  $mail->AltBody = strip_tags($message);
  if (file_exists($f_name)) {
    $mail->addAttachment($f_name, 'заказ_' . date('Y-m-d') . '.csv');
  }

  if ($mail->send()) {
    echo 'Письмо успешно отправлено!';
    unset($_SESSION['order_data']);
    header("Location: thanks_for_order.php");
    exit;
  } else {
    echo "Ошибка при отправке письма";
  }

  if (file_exists($f_name)) {
    unlink($f_name);
  }

} catch (Exception $e) {
  echo "Ошибка: {$mail->ErrorInfo}";

  if (file_exists($f_name)) {
    unlink($f_name);
  }
}
?>