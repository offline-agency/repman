# Configuration

## Mailer

To configure mailer transport, enter connection details in the `MAILER_DSN` environment variable

```
MAILER_DSN=smtp://user:pass@smtp.example.com
```

Read more: [transport setup](https://symfony.com/doc/current/mailer.html#transport-setup)

In addition, setup also `MAILER_SENDER` environment variable

```
MAILER_SENDER=mail_from@example.com
```

## Workers

To process messages asynchronously you must run worker:

```bash
bin/console messenger:consume async
```

Read more: [deploying to production](https://symfony.com/doc/current/messenger.html#deploying-to-production)

## Self-hosted GitLab

To integrate with self-hosted GitLab, enter the instance url in the `APP_GITLAB_API_URL` environment variable

```
APP_GITLAB_API_URL='https://gitlab.organization.lan'
```

## Self-hosted Storage

By default, Repman stores packages locally on disk. For production deployments, you can use S3-compatible storage.

### Local Storage (Default)

```env
STORAGE_SOURCE=storage.local
PROXY_DIST_DIR=%kernel.project_dir%/var/proxy
PACKAGES_DIST_DIR=%kernel.project_dir%/var/repo
```

### S3 Storage

To use AWS S3 or S3-compatible storage (MinIO, Ceph, etc.):

```env
STORAGE_SOURCE=storage.s3
STORAGE_AWS_BUCKET=my-bucket-name
STORAGE_AWS_REGION=eu-west-1
# When using S3, use relative paths:
PROXY_DIST_DIR=var/proxy
PACKAGES_DIST_DIR=var/repo
```

#### Authentication Options

**Option 1: AWS Credential Chain (Recommended for AWS/EKS)**

Uses the AWS SDK's default credential provider chain, which supports:

- Environment variables (`AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`)
- EKS Pod Identity / IRSA (IAM Roles for Service Accounts)
- ECS container credentials
- EC2 instance profile credentials

```env
STORAGE_AWS_OPAQUE_AUTH=false
# No key/secret needed - credentials obtained automatically
```

**Option 2: Explicit Credentials**

For environments where you need to provide credentials directly:

```env
STORAGE_AWS_OPAQUE_AUTH=true
STORAGE_AWS_KEY=your_access_key_id
STORAGE_AWS_SECRET=your_secret_access_key
```

#### S3-Compatible Storage (MinIO, Ceph, etc.)

For self-hosted S3-compatible storage:

```env
STORAGE_AWS_ENDPOINT=https://s3.myhost.com
STORAGE_AWS_PATH_STYLE_ENDPOINT=true
```
