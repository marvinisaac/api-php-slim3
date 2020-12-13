# PHP API with Slim 3

### Installation
1. Clone repo
2. Fill `.env` values
3. Run `./cmd/build-dev-image`
    - Builds image to be used during development
4. Add `./cmd/pre-commit` to `./git/hooks/pre-commit`
    - Fixes style errors
    - Runs PHPUnit tests
    - Bumps version number
5. Add `./cmd/pre-push` to `./git/hooks/pre-push`
    - Prevents `git push` command when there are failed tests
