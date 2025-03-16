# Simple Telegram Bot

*Simple Telegram Bot* is a project designed to use telegram bot feature using [MadelineProto](https://github.com/danog/MadelineProto).


## Features

- Reply when someone start conversation with your bot using simple text and buttons


## Installation

To install the project, follow these steps:

1. Clone the repository:
    ```sh
    git clone https://github.com/metasanjaya/simple-telegram-bot.git
    ```
2. Navigate to the project directory:
    ```sh
    cd simple-telegram-bot
    ```
3. Install the dependencies:
    ```sh
    composer install
    ```
4. Edit config file:
    ```sh
    cp config.example.json config.json
    edit config.json
    ```


## Requirements

- PHP v8.3
- View [MadelineProto Requirements](https://docs.madelineproto.xyz/docs/REQUIREMENTS.html)


## Usage

To start the script, run:
```sh
php start.php
```

Please use supervisord or pm2 for daemonize.


## Contributing

Contributions are welcome! Please fork the repository and submit a pull request.


## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.


## Contact

For any questions or feedback, please contact metasanjaya@gmail.com.