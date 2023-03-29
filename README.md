# Telegram Server Monitoring
> :warning: This project is under development.

This program create an Dashboard on Telegram with bars and icons of server resources.

![Sample](https://douglascarlini.com/cdn/img/monitor-server-php.png)

### Features

- [x] Create an dashboard on Telegram
- [x] Keep dashboard updated in real time
- [x] Show icons based in resources limit usage
- [x] Send alerts to admin for overflow limit usage
- [x] Monitoring itself and report admin in case of crash

### Roadmap

- [ ] Send e-mails with error reports
- [ ] Keep itself always running
- [ ] Check Telegram limit rate
- [ ] Create REST API

### Bônus

- [ ] Rewrite in Go

### Requirements
> This program run shell commands, check permissions.

- Tested on Linux [Debian](https://www.debian.org/) 11 using [PHP](https://www.php.net/) 7.4

### Configuration
Copy `.env-example` file to `.env`, set your configs and load running this on terminal:

```bash
set -a && source .env && set +a
```

#### Configs

- `DISK` is your main disk
- `API_KEY` is your Telegram Bot Token
- `CHAT_ID` is the chat ID to send reports
- `ADMIN_CHAT_ID` is your private chat of admin
- `NAME` is the server name to identify on reports
- `ASTERISK` is to monitoring Asterisk VoIP software

### Deploy
Run `bash run.sh` on terminal, this will run `index.php` and `admin.php` in background.

#### Kill all Background Tasks
> :warning: This will kill all `php index.php` and `php admin.php` processes.

```bash
kill -kill $(ps aux | grep "php admin.php" | awk '{print $2}')
kill -kill $(ps aux | grep "php index.php" | awk '{print $2}')
```