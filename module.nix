{ config, lib, pkgs, ... }:

with lib;

let
  cfg = config.services.php-tg-bot;
in
{
  options.services.php-tg-bot = {
    enable = mkEnableOption "PHP Telegram bot service";

    package = mkOption {
      type = types.package;
      default = pkgs.callPackage ./default.nix {};
      description = "Package providing the php-tg-bot with its vendor deps.";
    };

    workingDirectory = mkOption {
      type = types.path;
      default = cfg.package;
      description = "Working directory the bot runs in (should contain src and vendor).";
    };

    user = mkOption {
      type = types.str;
      default = "php-tg-bot";
      description = "System user to run the service as.";
    };

    group = mkOption {
      type = types.str;
      default = "php-tg-bot";
      description = "System group to run the service as.";
    };

    environment = mkOption {
      type = with types; attrsOf str;
      default = {};
      example = { TELEGRAM_BOT_TOKEN = "xxxx"; APP_ENV = "production"; };
      description = "Environment variables for the bot (e.g., TELEGRAM_BOT_TOKEN).";
    };

    serviceConfig = mkOption {
      type = types.attrs;
      default = {};
      description = "Extra systemd Service options to merge.";
    };
  };

  config = mkIf cfg.enable {
    users.users.${cfg.user} = {
      isSystemUser = true;
      group = cfg.group;
    };
    users.groups.${cfg.group} = {};

    systemd.services.php-tg-bot = {
      description = "PHP Telegram Bot";
      wantedBy = [ "multi-user.target" ];
      after = [ "network-online.target" ];
      wants = [ "network-online.target" ];

      environment = cfg.environment;

      serviceConfig = mkMerge [
        {
          Type = "simple";
          User = cfg.user;
          Group = cfg.group;
          ExecStart = "${cfg.package}/bin/php-tg-bot";
          Restart = "on-failure";
          RestartSec = 5;
          Environment = [ "PHP_INI_SCAN_DIR=" "LC_ALL=C.UTF-8" ];
          DynamicUser = false;
        }
        cfg.serviceConfig
      ];
    };
  };
}

