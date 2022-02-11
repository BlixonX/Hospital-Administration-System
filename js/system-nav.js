const buttons = ['search', 'appointments', 'add', 'remove', 'logout'];

for(let i=0;i<buttons.length;i++)
{
    try
    {
        var btn = document.getElementById(buttons[i])
        btn.addEventListener('mouseup', function()
        {
            let url = window.location.href;
            if(url.indexOf('?') > -1)
                for(var e=url.length-1; e >= url.indexOf('?');e--)
                    url = url.slice(0, -1);

            window.location.href = url+"?page="+buttons[i];
        })
    } catch(e){}
}