from connect import db
import csv
from datetime import datetime

cursor = db.cursor()

with open("dataset\epl_results_2022-23.csv") as file:
    reader = csv.reader(file)
    cursor.executemany(
        "INSERT INTO matches VALUES (%s, %s, %s, %s)",
        [
            (
                match[2],
                match[3],
                match[4],
                datetime.strptime(f"{match[0]} {match[1]}", "%d/%m/%Y %H:%M"),
            )
            for match in list(reader)
        ],
    )
    db.commit()
    cursor.close()
