{
  description = "Dev shell for php-tg-bot";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixpkgs-unstable";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        pkgs = import nixpkgs { inherit system; };
        myPhp = pkgs.php84.withExtensions ({ all, enabled }:
          enabled ++ (with all; [
            curl
            mbstring
            pdo
            pdo_mysql
            openssl
          ])
        );
      in
      {
        devShells.default = pkgs.mkShell {
          packages = [
            myPhp
            myPhp.packages.composer
          ];

          shellHook = ''
            echo "PHP $(php -v | head -n1)"
            echo "Composer $(composer --version)"
          '';
        };
      }
    );
}
