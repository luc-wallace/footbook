from connect import db
import csv

cursor = db.cursor()

with open("./dataset/epl_clubs_info_2022-23.csv") as file:
    reader = csv.reader(file)
    cursor.executemany(
        "INSERT INTO teams VALUES (%s, %s, %s, %s, %s)", list(reader)[1:]
    )
    db.commit()
    cursor.close()
