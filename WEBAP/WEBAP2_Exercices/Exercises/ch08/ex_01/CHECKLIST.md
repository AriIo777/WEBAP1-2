# Project checklist

## Requirements of the module
- [ ] The page is NEVER reloaded during the whole use of the app (single page application)
- [ ] At least one EXTERNAL JSON API is used, loaded with PHP
- [ ] At least one INTERNAL data source (database, file or PHP-generated data)
- [ ] Complex data (objects, arrays of objects, nested data) is exchanged asynchronously
- [ ] The server offers an API (action/endpoint based, JSON answers, status codes)
- [ ] The division of tasks between client and server is analysed and justified (PLANNING.md, section 6)

## Code quality
- [ ] Modular structure: api/ (one file per resource + helpers.php), js/app.js, css/app.css
- [ ] The same answer format for all endpoints ({success, data} / {success, error})
- [ ] No duplicated code (functions for repeated tasks)
- [ ] Meaningful names, indentation, comments where necessary
- [ ] Client code structured: configuration, state, api(), render functions, events, start

## Robustness
- [ ] Error of the external service → understandable message, the app keeps working
- [ ] Empty or wrong input is handled (client AND server)
- [ ] Loading indicator for slow requests
- [ ] Optional: answers of the external service are cached

## Documentation and presentation
- [ ] PLANNING.md complete and up to date
- [ ] API documented (API.md)
- [ ] Short demo (5 minutes): idea, live demo, one interesting piece of code, what you learned
