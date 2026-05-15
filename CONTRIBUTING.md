# Contributing

Thanks for considering contributing to `programmerhasan/seo`.

## Setup

```bash
git clone https://github.com/programmerhasan/seo.git
cd seo
composer install
```

## Quality checks

Before opening a pull request, run:

```bash
composer ci
```

## Pull request guidelines

- Keep the core package lightweight.
- Do not add heavy third-party dependencies without a strong reason.
- Add tests for new behavior.
- Update docs when public APIs change.
- Keep backwards compatibility unless the change is planned for a major release.
