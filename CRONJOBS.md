`[MIMUTE]` `[HOUR]` `[Day of Month]` `[Month]` `[Day of Week]` `[User]` `[Command]`

#### каждые 15 минут, в 14, 29, 44 и 59 минут
```
14,29,44,59 * * * * re script.sh
```


#### каждый час в 59 минут
```
59 * * * * re script.sh
```

#### каждый день в 0:00
```
0 0 * * * re script.sh
```
