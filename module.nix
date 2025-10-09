{ config, lib, pkgs, ... }:

with lib;

let
  cfg = config.services.php-tg-bot;
in
{
  options.services.php-tg-bot = {
    enable = mkEnableOption "PHP Telegram bot service";

    user = mkOption {
      type = types.str;
      default = "php-tg-bot";
    };

    group = mkOption {
      type = types.str;
      default = "php-tg-bot";
    };

    package = mkOption {
      type = types.package;
      default = pkgs.callPackage ./. {};
    };

    phpPackage = mkOption {
      type = types.package;
      default = pkgs.php84;
    };

    environment = mkOption {
      type = with types; attrsOf str;
      default = {};
      example = { TELEGRAM_BOT_TOKEN = "xxxx"; APP_ENV = "production"; };
    };

    fpm = {
      enable = mkOption {
        type = types.bool;
        default = false;
      };

      poolName = mkOption {
        type = types.str;
        default = "php-tg-bot";
      };

      settings = mkOption {
        type = with types; attrsOf (oneOf [ str int bool ]);
        default = {};
        example = {
          "pm" = "dynamic";
          "pm.max_children" = 10;
          "listen" = "/run/phpfpm/php-tg-bot.sock";
        };
      };
    };

    serviceConfig = mkOption {
      type = types.attrs;
      default = {};
    };

    nginx = {
      enable = mkOption {
        type = types.bool;
        default = false;
      };

      serverName = mkOption {
        type = types.str;
        default = "php-tg-bot.local";
      };

      webroot = mkOption {
        type = types.path;
        default = "/var/www/php-tg-bot";
      };

      webhookPath = mkOption {
        type = types.str;
        default = "/";
      };

      manageWebroot = mkOption {
        type = types.bool;
        default = true;
      };
    };
  };

  config = mkIf cfg.enable {
    users.users.${cfg.user} = {
      isSystemUser = true;
      group = cfg.group;
    };

    users.groups.${cfg.group} = {};

    services.phpfpm = mkIf cfg.fpm.enable {
      enable = true;
      pools.${cfg.fpm.poolName} = {
        phpPackage = cfg.phpPackage;
        user = cfg.user;
        group = cfg.group;
        settings = mkMerge [
          {
            "pm" = "dynamic";
            "pm.max_children" = 5;
            "pm.start_servers" = 2;
            "pm.min_spare_servers" = 1;
            "pm.max_spare_servers" = 3;
            "listen" = "/run/phpfpm/${cfg.fpm.poolName}.sock";
            "listen.owner" = cfg.user;
            "listen.group" = cfg.group;
            "listen.mode" = "0660";
            "env" = cfg.environment;
          }
          cfg.fpm.settings
        ];
      };
    };

    services.nginx = mkIf cfg.nginx.enable {
      enable = true;
      virtualHosts.${cfg.nginx.serverName} = {
        root = cfg.nginx.webroot;
        extraConfig = ''
          index index.php;
        '';
        locations = {
          "${cfg.nginx.webhookPath}" = {
            extraConfig = ''
              try_files $uri /src/index.php?$args;
            '';
          };

          # Route all PHP scripts to the dedicated PHP-FPM pool
          "~ \\.php$" = {
            extraConfig = ''
              include ${pkgs.nginx}/conf/fastcgi_params;
              fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
              fastcgi_pass unix:/run/phpfpm/${cfg.fpm.poolName}.sock;
              fastcgi_index index.php;
            '';
          };
        };
      };
    };

    # We only manage it if explicitly enabled.
    systemd.tmpfiles.rules = mkIf cfg.nginx.manageWebroot [
      "d ${cfg.nginx.webroot} 0755 ${cfg.user} ${cfg.group} -"
    ];

    system.activationScripts.phpTgBotWebroot = mkIf (cfg.nginx.manageWebroot) {
      deps = [ "nginx" ];
      text = let
        pkgRoot = "${cfg.package}/share/php/${cfg.package.pname}";
      in ''
        set -euo pipefail
        echo "Synchronizing php-tg-bot webroot from ${pkgRoot} -> ${cfg.nginx.webroot}"
        if [ -d "${pkgRoot}" ]; then
          mkdir -p "${cfg.nginx.webroot}"
          # Sync everything except .env to allow local overrides
          ${pkgs.rsync}/bin/rsync -a --delete --exclude '.env' "${pkgRoot}/" "${cfg.nginx.webroot}/"
          chown -R ${cfg.user}:${cfg.group} "${cfg.nginx.webroot}"
        else
          echo "Warning: package root ${pkgRoot} not found; skip sync" >&2
        fi
      '';
    };
  };
}

