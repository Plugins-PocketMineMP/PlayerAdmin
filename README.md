## PlayerAdmin

A PocketMine-MP Plugin | PlayerAdmin


Feature
------

* Warn system
* Instantly ban or ip-ban
* Can see player's address and DeviceOS, DeviceModel

## Usage
|command|description|
|---|---|
|/playeradmin|PlayerAdmin's main command.|
|/pa|aliases of /playeradmin|

You can warn player or ban player in Manage ui.

1. type /pa or /playeradmin command.

2. select the type you want to manage player.

3. select or input player name.

4. you can see player's address, device model, device os, total warn.

## Warn system

If the player's warn count exceeds the value stored in the data, the player cannot connect with message "You are banned. Warn count: [warn]".

The default value is 5.

## Future Plan

* Mute system
* clientId, clientUUID, device ban
* and more

## Config
PlayerData config
```yaml
---
alvin0319:
  device: Windows10
  model: SAMSUNG ELECTRONICS CO., LTD. 700A7K
  address: 127.0.0.1
...
```

WarnData config
```yaml
---
ban-count: 5 ## this is limit of warn
player:
  alvin0319: 1 ## this is total warn
...
```

## Pictures

![](https://raw.githubusercontent.com/alvin0319/PlayerAdmin/master/images/1.PNG)
![](https://raw.githubusercontent.com/alvin0319/PlayerAdmin/master/images/2.PNG)
![](https://raw.githubusercontent.com/alvin0319/PlayerAdmin/master/images/3.PNG)
![](https://raw.githubusercontent.com/alvin0319/PlayerAdmin/master/images/4.PNG)
![](https://raw.githubusercontent.com/alvin0319/PlayerAdmin/master/images/5.PNG)
![](https://raw.githubusercontent.com/alvin0319/PlayerAdmin/master/images/6.PNG)
