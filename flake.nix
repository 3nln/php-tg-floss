{
  description = "php-tg-bot - PHP community";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixpkgs-unstable";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        pkgs = nixpkgs.legacyPackages.${system};
      in
      {
        formatter = pkgs.alejandra;

        packages.default = pkgs.callPackage ./default.nix { inherit pkgs; };

        devShells.default = pkgs.callPackage ./shell.nix { inherit pkgs; };
      }
    ) // {
      nixosModules.default = import ./module.nix self;
    };
}
