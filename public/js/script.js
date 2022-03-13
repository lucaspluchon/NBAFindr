players_placeholder = [
    'Stephen Curry',
    'Kevin Durant',
    'LeBron James',
    'James Harden',
    'Nikola Jokic'
]

new TypeWriter(
    '#search_input',
    players_placeholder,
    {writeDelay: 70, holdOnceWritten: 2500}
);

function add_compare(checkbox)
{
    if (window.player_to_compare === undefined)
        window.player_to_compare = []
    if (checkbox.checked)
    {
        window.player_to_compare.push(checkbox.name)
        if (window.player_to_compare.length === 2)
            window.location.replace("/compare?player1=" + window.player_to_compare[0] + "&player2=" + window.player_to_compare[1]);
    }
    else
    {
        window.player_to_compare.splice(window.player_to_compare.indexOf(checkbox.name), 1)
    }
}