<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title>Сертификат участника</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    background: #fff;
  }

  .certificate {
    width: 1000px;
    height: 707px; /* A4 landscape ratio ~1.414 */
    margin: 0 auto;
    position: relative;
    background: linear-gradient(135deg, #f0f7f0 0%, #ffffff 40%, #e8f5e9 100%);
    border: 3px solid #2e7d32;
    overflow: hidden;
  }

  /* Декоративная рамка */
  .certificate::before {
    content: '';
    position: absolute;
    inset: 16px;
    border: 1px solid #4caf50;
    pointer-events: none;
  }

  .certificate::after {
    content: '';
    position: absolute;
    inset: 22px;
    border: 1px solid #a5d6a7;
    pointer-events: none;
  }

  .content {
    position: relative;
    z-index: 1;
    padding: 60px 70px;
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  .header {
    text-align: center;
    margin-bottom: 10px;
  }

  .logo {
    font-size: 14px;
    font-weight: 700;
    color: #2e7d32;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    margin-bottom: 6px;
  }

  .logo span { font-size: 22px; }

  .header-line {
    width: 120px;
    height: 2px;
    background: linear-gradient(to right, transparent, #2e7d32, transparent);
    margin: 8px auto;
  }

  h1 {
    font-size: 36px;
    font-weight: 300;
    color: #1b5e20;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    text-align: center;
    margin-bottom: 6px;
  }

  .subtitle {
    font-size: 12px;
    color: #4caf50;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    text-align: center;
  }

  .body {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 20px 0;
  }

  .presented-to {
    font-size: 12px;
    color: #757575;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    margin-bottom: 16px;
  }

  .org-name {
    font-size: 32px;
    font-weight: 800;
    color: #1b5e20;
    margin-bottom: 16px;
    max-width: 700px;
    line-height: 1.2;
  }

  .for-project {
    font-size: 11px;
    color: #757575;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    margin-bottom: 8px;
  }

  .project-name {
    font-size: 22px;
    font-weight: 700;
    color: #2e7d32;
    max-width: 700px;
    margin-bottom: 24px;
    line-height: 1.3;
  }

  .nomination-badge {
    display: inline-block;
    background: #e8f5e9;
    border: 1px solid #a5d6a7;
    border-radius: 30px;
    padding: 8px 24px;
    font-size: 13px;
    font-weight: 600;
    color: #2e7d32;
    letter-spacing: 0.05em;
    margin-bottom: 30px;
  }

  .footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    border-top: 1px solid #e0e0e0;
    padding-top: 20px;
    margin-top: auto;
  }

  .footer-left, .footer-right {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 180px;
  }

  .footer-center {
    text-align: center;
  }

  .qr-placeholder {
    width: 80px;
    height: 80px;
    background: #f5f5f5;
    border: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    color: #9e9e9e;
    margin: 0 auto 4px;
  }

  .footer-label {
    font-size: 10px;
    color: #9e9e9e;
    text-transform: uppercase;
    letter-spacing: 0.1em;
  }

  .cert-number {
    font-size: 12px;
    color: #757575;
    margin-bottom: 4px;
  }

  .cert-number strong {
    color: #2e7d32;
    font-size: 14px;
  }

  .date-issued {
    font-size: 12px;
    color: #757575;
  }

  .signature-line {
    width: 160px;
    border-bottom: 1px solid #9e9e9e;
    margin: 0 auto 4px;
  }

  .signature-label {
    font-size: 10px;
    color: #9e9e9e;
    text-transform: uppercase;
  }

  /* Декоративные элементы */
  .corner-decor {
    position: absolute;
    width: 60px;
    height: 60px;
    opacity: 0.15;
  }
  .corner-tl { top: 30px; left: 30px; border-top: 3px solid #2e7d32; border-left: 3px solid #2e7d32; }
  .corner-tr { top: 30px; right: 30px; border-top: 3px solid #2e7d32; border-right: 3px solid #2e7d32; }
  .corner-bl { bottom: 30px; left: 30px; border-bottom: 3px solid #2e7d32; border-left: 3px solid #2e7d32; }
  .corner-br { bottom: 30px; right: 30px; border-bottom: 3px solid #2e7d32; border-right: 3px solid #2e7d32; }

  .leaf-bg {
    position: absolute;
    font-size: 200px;
    opacity: 0.04;
    right: -20px;
    bottom: -20px;
    z-index: 0;
    color: #2e7d32;
  }
</style>
</head>
<body>
<div class="certificate">
  <div class="corner-decor corner-tl"></div>
  <div class="corner-decor corner-tr"></div>
  <div class="corner-decor corner-bl"></div>
  <div class="corner-decor corner-br"></div>
  <div class="leaf-bg">🌍</div>

  <div class="content">
    <div class="header">
      <div class="logo"><span>🌍</span> &nbsp; Проект «День Земли — каждый день!»</div>
      <div class="header-line"></div>
      <h1>Сертификат участника</h1>
      <div class="subtitle">Earth Day — Every Day! &nbsp; Certificate of Participant</div>
    </div>

    <div class="body">
      <div class="presented-to">Настоящим удостоверяется, что</div>
      <div class="org-name">{{ $organizationName }}</div>

      <div class="for-project">награждается за реализацию проекта</div>
      <div class="project-name">{{ $projectTitle }}</div>

      <div class="nomination-badge">
        📋 Номинация: {{ $nominationName }}
      </div>
    </div>

    <div class="footer">
      <div class="footer-left">
        <div class="qr-placeholder">QR</div>
        <div class="footer-label">Проверить на earthday.ru/certificates</div>
      </div>

      <div class="footer-center">
        <div class="cert-number">№ <strong>{{ $certificateNumber }}</strong></div>
        <div class="date-issued">{{ $issuedDate }}</div>
      </div>

      <div class="footer-right">
        <div class="signature-line"></div>
        <div class="signature-label">Председатель Оргкомитета</div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
