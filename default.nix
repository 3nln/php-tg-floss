{ pkgs ? import <nixpkgs> {} }:

let
  # Keep the same PHP version and extension set as before
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

  # Provide the lock file explicitly (even if .gitignored)
  composerLock = builtins.path { path = ./composer.lock; name = "composer.lock"; };

  # Use the php build with our extensions
  php = php;

  # Fill this with the result from a first failed build (will suggest the hash)
  vendorHash = "sha256-AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=";

  # Link an executable that runs the bot entrypoint
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