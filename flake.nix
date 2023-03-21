{
  description = "marcosh/lamphpda";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixpkgs-unstable";
  };

  outputs = inputs @ {
    self,
    flake-parts,
    ...
  }:
    flake-parts.lib.mkFlake {inherit inputs;} {
      # This flake is for Linux (x86) and Apple (darwin) systems
      # If you need more systems, inspect `nixpkgs.lib.systems.flakeExposed` and
      # add them to this list.
      #
      # $ nix repl "<nixpkgs>"
      # nix-repl> lib.systems.flakeExposed
      systems = ["x86_64-linux" "aarch64-linux"];

      perSystem = {
        pkgs,
        system,
        ...
      }: {
        # Run `nix fmt` to reformat the nix files
        formatter = pkgs.alejandra;

        # Run `nix develop` to enter the development shell
        devShells.default = pkgs.mkShellNoCC {
          name = "php-devshell";

          buildInputs = [
            pkgs.php81
            pkgs.php81.packages.composer
          ];
        };
      };
    };
}
