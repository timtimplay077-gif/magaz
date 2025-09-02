<table width="100%" cellpadding="0" cellspacing="0" role="presentation"
  style="background:#f4f6f8;padding:20px 0;font-family:Arial, sans-serif;">
  <tr>
    <td aling="center">
      <table width="600" cellpadding="0" cellspacing="0" role="presentation"
        style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,0.08);">
        <tr>
          <td style="padding:20px 24px;border-bottom:1px solid #eef0f2;">
            <h2 style="margin:0;font-size:20px;color:#0f1724;">Реквізити контакту</h2>
            <p style="margin:6px 0 0;font-size:13px;color:#667085;">Нижче наведено контактні дані користувача.</p>
          </td>
        </tr>
        <tr>
          <td style="padding:18px 24px;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
              style="font-size:14px;color:#101828;">
              <tr>
                <td style="padding:10px 0;border-bottom:1px dashed #eef0f2;">
                  <strong style="display:block;color:#0b1220;">Ім`я</strong>
                  <span style="color:#334155;">{{first_name}}</span>
                </td>
              </tr>
              <tr>
                <td style="padding:10px 0;border-bottom:1px dashed #eef0f2;">
                  <strong style="display:block;color:#0b1220;">Прізвище</strong>
                  <span style="color:#334155;">{{last_name}}</span>
                </td>
              </tr>
              <tr>
                <td style="padding:10px 0;border-bottom:1px dashed #eef0f2;">
                  <strong style="display:block;color:#0b1220;">Номер телефону</strong>
                  <a href="tel:{{phone_raw}}" style="color:#0b66ff; text-decoration:none;">{{phone}}</a>
                </td>
              </tr>
              <tr>
                <td style="padding:10px 0;border-bottom:1px dashed #eef0f2;">
                  <strong style="display:block;color:#0b1220;">Електронна пошта</strong>
                  <a href="mailto:{{email_raw}}" style="color:#0b66ff; text-decoration:none;">{{email}}</a>
                </td>
              </tr>
              <tr>
                <td style="padding:10px 0;border-bottom:1px dashed #eef0f2;">
                  <strong style="display:block;color:#0b1220;">Місто</strong>
                  <span style="color:#334155;">{{city}}</span>
                </td>
              </tr>
              <tr>
                <td style="padding:10px 0;border-bottom:1px dashed #eef0f2;">
                  <strong style="display:block;color:#0b1220;">Регіон</strong>
                  <span style="color:#334155;">{{region}}</span>
                </td>
              </tr>
              <?php if (!empty($address)): ?>
                <tr>
                  <td style="padding:10px 0;border-bottom:1px dashed #eef0f2;">
                    <strong style="display:block;color:#0b1220;">Адреса</strong>
                    <span style="color:#334155;">{{address}}</span>
                  </td>
                </tr>
              <?php endif; ?>
              <tr>
                <td style="padding:10px 0;">
                  <strong style="display:block;color:#0b1220;">Відділення Нової Пошти</strong>
                  <span style="color:#334155;">{{nova_poshta}}</span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding:14px 24px;background:#fafafa;border-top:1px solid #eef0f2;">
            <p style="margin:0;font-size:12px;color:#98a2b3;">Це авто-згенерований лист. Будь ласка, не відповідайте на
              нього.</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>

</html>