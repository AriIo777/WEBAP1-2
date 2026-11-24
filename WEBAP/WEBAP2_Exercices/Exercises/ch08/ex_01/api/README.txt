The server API of your project.

- helpers.php   sendJson(), sendError(), requireMethod(), getInput()  (copy them from chapter 6)
- one PHP file per resource, e.g. items.php with ?action=list|get|add|update|delete
- external services: use the constants of common/config.php or add your own ones here (config.php)
- document every endpoint in ../API.md
