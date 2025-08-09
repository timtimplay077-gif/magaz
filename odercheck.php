<?php
$mail_host = "smtp.gmail.com";
$mail_username = "pykpykdaun69@gmail.com";
$mail_to = "timtimplay077@gmail.com";
$message = '
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f4f6f8;padding:20px 0;font-family:Arial, sans-serif;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,0.08);">
        <!-- header -->
        <tr>
          <td style="padding:20px 24px;border-bottom:1px solid #eef0f2;">
            <h2 style="margin:0;font-size:20px;color:#0f1724;">Реквізити контакту</h2>
            <p style="margin:6px 0 0;font-size:13px;color:#667085;">Нижче наведено контактні дані користувача.</p>
          </td>
        </tr>

        <!-- content -->
        <tr>
          <td style="padding:18px 24px;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size:14px;color:#101828;">
              <tr>
                <td style="padding:10px 0;border-bottom:1px dashed #eef0f2;">
                  <strong style="display:block;color:#0b1220;">Ім`я</strong>
                  <span style="color:#334155;">{{first_name}}</span>
                </td>
              </tr>

              <!-- Прізвище -->
              <tr>
                <td style="padding:10px 0;border-bottom:1px dashed #eef0f2;">
                  <strong style="display:block;color:#0b1220;">Прізвище</strong>
                  <span style="color:#334155;">{{last_name}}</span>
                </td>
              </tr>

              <!-- Номер телефону -->
              <tr>
                <td style="padding:10px 0;border-bottom:1px dashed #eef0f2;">
                  <strong style="display:block;color:#0b1220;">Номер телефону</strong>
                  <a href="tel:{{phone_raw}}" style="color:#0b66ff; text-decoration:none;">{{phone}}</a>
                </td>
              </tr>

              <!-- Електронна пошта -->
              <tr>
                <td style="padding:10px 0;border-bottom:1px dashed #eef0f2;">
                  <strong style="display:block;color:#0b1220;">Електронна пошта</strong>
                  <a href="mailto:{{email_raw}}" style="color:#0b66ff; text-decoration:none;">{{email}}</a>
                </td>
              </tr>

              <!-- Місто -->
              <tr>
                <td style="padding:10px 0;border-bottom:1px dashed #eef0f2;">
                  <strong style="display:block;color:#0b1220;">Місто</strong>
                  <span style="color:#334155;">{{city}}</span>
                </td>
              </tr>

              <!-- Регіон -->
              <tr>
                <td style="padding:10px 0;border-bottom:1px dashed #eef0f2;">
                  <strong style="display:block;color:#0b1220;">Регіон</strong>
                  <span style="color:#334155;">{{region}}</span>
                </td>
              </tr>

              <!-- Адреса -->
              <tr>
                <td style="padding:10px 0;">
                  <strong style="display:block;color:#0b1220;">Адреса</strong>
                  <span style="color:#334155;">{{address}}</span>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- footer -->
        <tr>
          <td style="padding:14px 24px;background:#fafafa;border-top:1px solid #eef0f2;">
            <p style="margin:0;font-size:12px;color:#98a2b3;">Це авто-згенерований лист. Будь ласка, не відповідайте на нього.</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>';
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
$headers .= "From: Серёжа <$mail_username>" . "\r\n";
$headers .= "Reply-To: $mail_username" . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();
mail($mail_to, "hello", $message, $headers);

?>