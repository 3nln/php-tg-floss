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
    };

    environment = mkOption {
      type = with types; attrsOf str;
      default = {};
      example = { TELEGRAM_BOT_TOKEN = "xxxx"; APP_ENV = "production"; };
    };

    serviceConfig = mkOption {
      type = types.attrs;
      default = {};
    };
  };

  config = mkIf cfg.enable {
    users.users.${cfg.user} = {
      isSystemUser = true;
      group = cfg.group;
    };

    users.groups.${cfg.group} = {};

    systemd.services.php-tg-bot = {
      description = "PHP Uzbekistan Telegram Assistant";

      wantedBy = [ "multi-user.target" ];
      after = [ "network-online.target" ];
      wants = [ "network-online.target" ];

      environment = cfg.environment;

      serviceConfig = mkMerge [
        {
          User = cfg.user;
          Group = cfg.group;
          Restart = "always";
          RestartSec = 5;
          DynamicUser = false;
          ExecStart = "${getBin cfg.package}/bin/php-tg-bot ${genArgs {cfg = cfg;}}";
          StateDirectory = cfg.user;
          StateDirectoryMode = "0750";
          # Hardening
          CapabilityBoundingSet = [
            "AF_NETLINK"
            "AF_INET"
            "AF_INET6"
          ];
          DeviceAllow = ["/dev/stdin r"];
          DevicePolicy = "strict";
          IPAddressAllow = "localhost";
          LockPersonality = true;
          # MemoryDenyWriteExecute = true;
          NoNewPrivileges = true;
          PrivateDevices = true;
          PrivateTmp = true;
          PrivateUsers = true;
          ProtectClock = true;
          ProtectControlGroups = true;
          ProtectHome = true;
          ProtectHostname = true;
          ProtectKernelLogs = true;
          ProtectKernelModules = true;
          ProtectKernelTunables = true;
          ProtectSystem = "strict";
          ReadOnlyPaths = ["/"];
          RemoveIPC = true;
          RestrictAddressFamilies = [
            "AF_NETLINK"
            "AF_INET"
            "AF_INET6"
          ];
          RestrictNamespaces = true;
          RestrictRealtime = true;
          RestrictSUIDSGID = true;
          SystemCallArchitectures = "native";
          SystemCallFilter = [
            "@system-service"
            "~@privileged"
            "~@resources"
            "@pkey"
          ];
          UMask = "0027";
        }
        cfg.serviceConfig
      ];
    };
  };
}

