function Message-Info {
    Param($Message)
    Write-Host " $Message " -ForegroundColor Black -BackgroundColor White
}

$ScreenBufferSize = ($Host.UI.RawUI.BufferSize).Width
$line = "";
for ($i = 0; $i -lt $ScreenBufferSize; $i++) {
    $line = "$line "
}

function Toast-Message {
    Param($Message, $Color)

    $LineAfterMessage = ""
    for ($i = 0; $i -lt $ScreenBufferSize - $Message.Length - 2; $i++) {
        $LineAfterMessage = "$LineAfterMessage "
    }

    Write-Host $line -ForegroundColor Black -BackgroundColor $Color
    Write-Host " $Message $LineAfterMessage" -ForegroundColor Black -BackgroundColor $Color
    Write-Host $line -ForegroundColor Black -BackgroundColor $Color
    Write-Host ""
}

function Toast-Info {
    Param($Message)

    Toast-Message $Message -Color 'Green'
}

function Toast-Warning {
    Param($Message)

    Toast-Message $Message -Color 'Yellow'
}

function Toast-Error {
    Param($Message)

    Toast-Message $Message -Color 'Red'
}