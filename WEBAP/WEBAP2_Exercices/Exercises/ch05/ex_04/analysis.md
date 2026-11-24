# Exercise 5.4 – Client or server?

For each scenario, decide WHERE each task should be done (client = JavaScript in the browser,
server = PHP) and JUSTIFY your choice with the criteria of section 5.5
(security, amount of data, performance, reusability, availability of the data, API keys).

## Scenario A – Weather dashboard
Users save favourite cities; the page shows the current weather of each favourite (Open-Meteo).

| Task | Client or server? | Justification |
|---|---|---|
| Store the list of favourites | | |
| Load the weather from Open-Meteo | | |
| Translate the weather code into a text | | |
| Sort the cards by temperature (button) | | |
| Check that a city name is not empty | | |

## Scenario B – Online quiz with a score table
Questions are stored in a database. The score of each player is stored at the end.

| Task | Client or server? | Justification |
|---|---|---|
| Choose 10 random questions | | |
| Check if an answer is correct | | |
| Show the next question | | |
| Calculate the final score | | |
| Show the 10 best players | | |

## Scenario C – Your own project idea
Describe your idea in 3 sentences and fill in the table for at least 5 tasks.

## Planning the API
For scenario A, list the endpoints the server should offer (method, URL, parameters, answer).
