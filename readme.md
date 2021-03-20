# Tele Step Handler

Для упрощениея работы с телеграмом

## Installation

Install via composer
```bash
composer require aqjw/tele-step-handler
```


Publish config
```bash
php artisan vendor:publish --provider="Aqjw\TeleStepHandler\TeleStepHandlerServiceProvider" --tag="config"
```


## Usage



```php
namespace App\Telegram\Steps;

use Aqjw\TeleStepHandler\TeleStepHandlerAbstract;
use Aqjw\TeleStepHandler\Steps\TeleStepCommand;
use Aqjw\TeleStepHandler\Steps\TeleStepButton;

class MainSteps extends TeleStepHandlerAbstract
{
    public function handler()
    {
        return [
            new TeleStepCommand('/start', function () {
                $this->bot->sendMessage([
                    'text' => 'This is start command',
                    'reply_markup' => [
                        'inline_keyboard' => [[['text' => 'Do something', 'callback_data' => 'do_something']]]
                    ]
                ]);

                return false; // stop checking other steps
            }),

            new TeleStepButton('do_something', function () {
                $this->bot->sendMessage(['text' => 'Something did']);

                return false; // stop checking other steps
            }),
        ];
    }

    public function trigger($args)
    {
        return true;
    }
}

```


## License
[MIT](https://choosealicense.com/licenses/mit/)