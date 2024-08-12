# Trademark scraper - test task for Underhood

A PHP CLI tool for searching and processing trademark data.

## Requirements

- PHP 8.2 or higher

## Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/ArenGr/trademark-scraper
   cd trademark-scraper
   ```
2. **Install Dependencies**
   ```bash
   composer install
   ```
3. **Set Up Environment Variables**
     ```bash
      cp .env.example .env
      ```
4. **Usage**
   ```bash
   php index.php <search-word>
   ```




# SQL Query

```sql
SELECT
	p.date,
	sum(p.quantity * pl.price) as total
FROM
	products p
LEFT JOIN price_log pl ON
	p.product_id = pl.product_id
	AND pl.date = (
	SELECT
		MAX(date)
	FROM
		price_log
	WHERE
		product_id = p.product_id
		AND date <= p.date
        )
WHERE
	p.date BETWEEN "2020-01-01" AND "2020-01-10"
GROUP BY
	p.date
ORDER BY
	date
