start = document.getElementById('start');
end = document.getElementById('end');

start.addEventListener('change', function(e)
{
    end.value = e.target.value;
});

end.addEventListener('change', function()
{
    if(end.value < start.value)
        end.value = start.value;
});