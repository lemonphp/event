lemonphp/event
===
A simple event dispatcher

Usage
---
```
use Lemonphp\Event\Event;
use Lemonphp\Event\Dispatcher;

$dispatcher = new Dispatcher();

$dispatcher->on('event.name', function(Event $event) {
    echo $event->getName() . ' is fired';
});

$dispatcher->trigger('event.name');
```

Changelog
---
See [CHANGELOG.md](https://github.com/lemonphp/event/blob/master/CHANGELOG.md)

Contributing
---
Please report any bugs or add pull requests on [Github Issues](https://github.com/lemonphp/event/issues).

License
---
This project is released under the MIT License.