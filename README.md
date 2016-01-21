Package lemonphp/event
===
[![Build Status](https://travis-ci.org/lemonphp/event.svg?branch=master)](https://travis-ci.org/lemonphp/event)
[![Coverage Status](https://coveralls.io/repos/github/lemonphp/event/badge.svg?branch=master)](https://coveralls.io/github/lemonphp/event?branch=master)

A simple event dispatcher

Usage
---

```
use Lemon\Event\Event;
use Lemon\Event\Dispatcher;

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