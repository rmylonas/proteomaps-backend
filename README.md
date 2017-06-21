#Backend for MixMHCp

This is the backend for the MixMHCp web project. It is communicating with the [MixMHCp_ng](https://gitlab.isb-sib.ch/Targetome/MixMHCp_ng).

It will call the script [MixMHCp](https://github.com/GfellerLab/MixMHCp) created by David Gfeller's lab.

The code is written in PHP and uses the SLIM framework. It is based on a simplified version of [ViKM](https://www.vital-it.ch/research/software/ViKM).


##Prerequisites

- Java 8 with X11
- Perl with `List::MoreUtils`


## Package installation

To install the required PHP libraries:

````
php composer.phar update
````

