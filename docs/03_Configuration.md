# Configuration

You can change the configuration by editing .phpsa.yml in the root directory.
The blame setting can show the git information about the author of the line.

You can also set a language level setting. This will disable all analyzers that require a higher version than specified here automatically.

For a finer configuration you can enable/disable every single analyzer there is. Some of them (like MissingDocblock) will have extra settings besides enabled. Here it's possible to configure the analyzer. The MissingDocblock analyzer for example can let you choose for which language constructs you want to see notices for a missing docblock.

Next: [Components](./04_Components.md)
