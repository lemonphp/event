Change log
===
All Notable changes to project will be documented in this file

v1.0.0 - YYYY-MM-DD
---
#### Added
- Nothing

#### Deprecated
- Nothing

#### Fixed
- Nothing

#### Removed
- Nothing

v0.3.0 - 2016-01-29
---
#### Changed
- Changed interface `DispatcherInterface` to `EventDispatcherInterface`
- Changed class `Dispatcher` to `EventDispatcher`
- Renamed method `Event::getName()` to `Event::getEventName()`

#### Added
- More test cases
- Trait `EventDispatcherAwareTrait`
- Interface `EventSubscriberInteface`
- Method `EventDispatherInterface::addSubscriber()` and `EventDispatherInterface::removeSubscriber()`

#### Deprecated
- Nothing

#### Fixed
- Nothing

#### Removed
- Nothing

v0.2.0 - 2016-01-22
---
#### Changed
- Changed package namespace from `LemonPHP\Event` to `Lemon\Event`
- Changed signature of method `DispatcherInterface::trigger()`
- Renamed method `Event::getName()` to `Event::getEventName()`

#### Added
- Test classes
- Configure files: `.php_cs`, `.travis.yml`

#### Deprecated
- Nothing

#### Fixed
- Nothing

#### Removed
- Nothing

v0.1.0 - 2015-12-30
---
#### Added
- Initialize project
