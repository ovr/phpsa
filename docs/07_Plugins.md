# Plugins

## CodeClimate

We are still working on full integration with CodeClimate, but you can try it locally:

First, you need to build the `PHPSA` Docker image:

```sh
docker build --no-cache=true -t codeclimate/codeclimate-phpsa --file ./plugins/codeclimate/Dockerfile .
```

Next enable `PHPSA` in configuration file `.codeclimate.yml`:

```yaml
---
engines:
  phpsa:
    enabled: true
```

And run CodeClimate CLI tool:

```sh
codeclimate analyze --dev
```
