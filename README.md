# expert-overflow-backend

# Windows PS
Если вы столкнулись с ошибкой 
```powershell
 File Some-kind-of-script.ps1 cannot be loaded. The file Some-kind-of-script.ps1 is not digitally signed. 
 You cannot run this script on the current system. For more information about running scripts 
 and setting execution policy, see about_Execution_Policies at https://go.microsoft.com/fwlink/?LinkID=135170.
```
Выполните следующий сценарий

```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
```