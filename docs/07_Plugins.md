# Plugins

## CodeClimate

We still working on full integration with CodeClimate but you can try it localy:

First, you need to build `PHPSA` Docker image:

```sh
docker build --no-cache=true -t codeclimate/codeclimate-phpsa --file ./plugins/codeclimate/Dockerfile .
```

Next enable `PHPSA` in configuration `.codeclimate.yml`:

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
