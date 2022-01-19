import tabula
import os

working_directory = os.path.dirname(os.path.abspath(__file__))
resources = working_directory + '/ressources'
output = working_directory +"/excel_output"

# Extaer los datos del pdf al DataFrame
# df = tabula.read_pdf("GeM-Bidding-2504454.pdf")
# lo convierte en un csv llamdo out.csv codificado con utf-8

file = 'GeM-Bidding-2504454.pdf'
tables = tabula.read_pdf(file,pages=1,multiple_tables=True,lattice=True)
print(tables[0])




