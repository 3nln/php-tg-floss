{ pkgs ? import <nixpkgs> {} }:

let
  php = pkgs.php84.buildEnv {
    extensions = ({ enabled, all }: enabled ++ (with all; [
      curl
      mbstring
      pdo
      pdo_mysql
      openssl
    ]));
  };
in

pkgs.php.buildComposerProject2 (finalAttrs: {
  pname = "php-tg-bot";
  version = "1.0.0";

  src = pkgs.lib.cleanSource ./.;

  composerLock = ./composer.lock;

  php = php;

  composerStrictValidation = false;

  vendorHash = "sha256-/B99+wEEQS8XaLI6fOB75e4C2yv5bY/Zb4G0Q8R25XY=";

  nativeBuildInputs = [ pkgs.makeWrapper ];
  postInstall = ''
    mkdir -p $out/bin
    makeWrapper ${php}/bin/php $out/bin/php-tg-bot \
      --set PHP_INI_SCAN_DIR "" \
      --add-flags "$out/src/index.php"
  '';

  meta = with pkgs.lib; {
    description = "Telegram bot in PHP packaged with Composer dependencies";
  };
})