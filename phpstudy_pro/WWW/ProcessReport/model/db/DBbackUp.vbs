Set objShell = CreateObject("Wscript.Shell")
return =objShell.Run(WScript.Arguments.Item(0)&" "&WScript.Arguments.Item(1)&" "&WScript.Arguments.Item(2)&" "&WScript.Arguments.Item(3)&" "&WScript.Arguments.Item(4),0,true) 
WScript.Quit(return)
Set objShell = Nothing



 

