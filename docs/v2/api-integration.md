# API integration

Callbacks:

- `/auth/{provider}/check`
- `/register/{provider}/check`
- `/user/token/{provider}/check`

## GitHub

Scopes:

- registration: `user:email`
- repositories: `read:org`, `repo`

## GitLab

Scopes:

- registration: `read_user`
- repositories: `api`

## Bitbucket

Scopes:

- registration: `email`
- repositories: `repository`, `webhook`
