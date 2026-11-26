# Exercise 2.2 – Understanding the answers

The folder contains three real answers: forecast-sample.json, geocoding-sample.json and rates-sample.json.
Open them in the browser (http://localhost:8080/Exercises/ch02/ex_02/forecast-sample.json).

## forecast-sample.json
1. Which data type is the value of "current"? And the value of "daily"?
2. Write the PHP expression for the current temperature ($data = json_decode($text, true)).
3. Write the JavaScript expression for the same value (data = the parsed JSON).
4. The daily values are stored as "parallel arrays". Explain what this means.
   Which expressions give the date and the maximum temperature of the SECOND day (PHP and JavaScript)?
5. Where can you find the unit of the temperature? Why is it useful to read it from the answer?

## geocoding-sample.json
6. How many results are there? Write the PHP expression for the latitude of the first result.
7. Which key is missing in the second result? What happens in PHP if you access it anyway?
   How can you avoid the problem (two possibilities)?

## rates-sample.json
8. "rates" is an object, not an array. Write a PHP foreach loop that outputs "1 EUR = 1.0864 USD" for every currency.
9. How do you access the rate of a currency whose code is stored in the variable $code?
