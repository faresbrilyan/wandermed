<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Password - WanderMed</title>
    <!--[if mso]>
    <noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
    <![endif]-->
</head>
<body style="margin:0; padding:0; background-color:#eef2f7; font-family:'Segoe UI',Arial,sans-serif; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">

<!-- Wrapper -->
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#eef2f7; padding:32px 0;">
<tr><td align="center">

<!-- Card container -->
<table border="0" cellpadding="0" cellspacing="0" width="580" style="max-width:580px; background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 8px 32px rgba(0,0,0,0.10);">

    <!-- ===== HEADER ===== -->
    <tr>
        <td align="center" style="background-color:#0e1d38; padding:32px 40px 28px;">
            <!-- Logo -->
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center">
                        <p style="margin:0; font-size:28px; font-weight:800; color:#ffffff; letter-spacing:-0.5px; line-height:1.2;">
                            Wander<span style="color:#ff7a00;">Med</span>
                        </p>
                        <p style="margin:6px 0 0; font-size:10px; color:rgba(255,255,255,0.45); letter-spacing:2px; text-transform:uppercase;">
                            Kesehatan Wisatawan Anda, Prioritas Kami
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- ===== HERO STRIP ===== -->
    <tr>
        <td align="center" style="background-color:#132240; padding:36px 40px 40px; border-bottom:3px solid #ff7a00;">

            <!-- Icon circle (table-based, no flex) -->
            <table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto 20px;">
                <tr>
                    <td align="center" valign="middle"
                        style="width:76px; height:76px; background-color:#ff7a00; border-radius:50%; text-align:center; vertical-align:middle;">
                        <!-- SVG key icon -->
                        <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iI2ZmZmZmZiIgZD0iTTEyLjY1IDEwQzExLjgzIDcuNjcgOS42MSA2IDcgNmMtMy4zMSAwLTYgMi42OS02IDZzMi42OSA2IDYgNmMyLjYxIDAgNC44My0xLjY3IDUuNjUtNEgxN3Y0aDR2LTRoMnYtNEgxMi42NXpNNyAxNGMtMS4xIDAtMi0uOS0yLTJzLjktMiAyLTIgMiAuOSAyIDItLjkgMi0yIDJ6Ii8+PC9zdmc+" 
                             width="36" height="36" alt="key" style="display:block; margin:20px auto;">
                    </td>
                </tr>
            </table>

            <p style="margin:0 0 10px; font-size:20px; font-weight:700; color:#ffffff; line-height:1.3;">
                Halo, {{ $name }}!
            </p>
            <p style="margin:0; font-size:14px; color:rgba(255,255,255,0.60); line-height:1.7; max-width:360px;">
                Kami menerima permintaan reset password untuk akun
                <strong style="color:#ff7a00;">{{ $accountType }}</strong>
                WanderMed Anda. Gunakan kode OTP di bawah ini.
            </p>
        </td>
    </tr>

    <!-- ===== OTP SECTION ===== -->
    <tr>
        <td align="center" style="background-color:#ffffff; padding:40px 40px 8px;">

            <p style="margin:0 0 20px; font-size:11px; font-weight:700; color:#8a9ab0; letter-spacing:2px; text-transform:uppercase;">
                Kode OTP Anda
            </p>

            <!-- OTP digit boxes via table -->
            <table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;">
                <tr>
                    @foreach(str_split($otp) as $digit)
                    <td align="center" valign="middle"
                        style="width:54px; height:64px; background-color:#0e1d38; border-radius:10px; margin:0 4px; padding:0 4px; text-align:center; vertical-align:middle;">
                        <span style="font-size:28px; font-weight:800; color:#ff7a00; font-family:'Courier New',Courier,monospace; line-height:64px;">{{ $digit }}</span>
                    </td>
                    @if(!$loop->last)
                    <td width="8" style="font-size:0;">&nbsp;</td>
                    @endif
                    @endforeach
                </tr>
            </table>

        </td>
    </tr>

    <!-- ===== EXPIRY NOTICE ===== -->
    <tr>
        <td style="padding:24px 40px 0;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="background-color:#fff8f0; border:1px solid #ffd6a0; border-radius:10px; padding:14px 18px; text-align:center;">
                        <p style="margin:0; font-size:13px; color:#b85c00; line-height:1.5;">
                            &#9200; Kode ini berlaku selama <strong>15 menit</strong> sejak email ini dikirim.<br>
                            Jangan bagikan kode ini kepada siapapun.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- ===== INFO BOX ===== -->
    <tr>
        <td style="padding:20px 40px 0;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="background-color:#f8f9ff; border-left:4px solid #ff7a00; border-radius:0 6px 6px 0; padding:14px 16px;">
                        <p style="margin:0; font-size:13px; color:#444; line-height:1.7;">
                            Masukkan kode OTP di atas pada halaman WanderMed, bersama dengan password baru Anda.
                            <br><br>
                            Jika Anda tidak meminta reset password, abaikan email ini &mdash;
                            <strong style="color:#112240;">akun Anda tetap aman.</strong>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- ===== DIVIDER ===== -->
    <tr>
        <td style="padding:28px 40px 0;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr><td style="border-top:1px solid #e9ecef; font-size:0;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>

    <!-- ===== FOOTER ===== -->
    <tr>
        <td align="center" style="background-color:#f8f9fb; padding:22px 40px 28px;">
            <p style="margin:0 0 6px; font-size:12px; color:#9aa8bc; line-height:1.7;">
                Email ini dikirim otomatis oleh sistem WanderMed.<br>
                Jangan membalas email ini.
            </p>
            <p style="margin:0; font-size:11px; color:#bcc5d3;">
                &#128274; WanderMed tidak pernah meminta password Anda melalui email.
            </p>
        </td>
    </tr>

</table>
<!-- / Card container -->

</td></tr>
</table>
<!-- / Wrapper -->

</body>
</html>
