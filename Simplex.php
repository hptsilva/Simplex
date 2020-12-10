<?php

/*
    Aluno: Humberto Pereira Teixeira Silva ------- Universidade Federal de Goiás - Regional Goiânia
    Professor: Cássio Vinhal
    Curso: Engenharia de Computação - Turma 25

    Obs: Este algoritmo Simplex é usado para maximizar problemas de programação linear.
         Funciona apenas para restrições maiores ou iguais a zero.
*/

    global $number_Constraints, $number_Decision, $matrix_Restrictions, $less, $bigger, $bigger_position1,
           $less_position1, $less_position2, $column_pivot, $pivot, $pivot_line, $object_Function, $aux;

    print ("Digite o número de variáveis de decisão: ");
    $number_Decision = (int) readline();
    print ("---------------------Para a Função Objetivo---------------------\n");

    for ($i = 1; $i <= $number_Decision; $i++) {

        print ("Digite o coeficiente da " . $i . "th variável de decisão: ");
        $object_Function[$i] = -(float)readline();
        if ($i == 1) {

            $less = $object_Function[$i];
            $less_position1 = $i;

        }
        else {

            if ($object_Function[$i] < $less){

                $less = $object_Function[$i];
                $less_position1 = $i;

            }
        }

    }

    print ("---------------------Para as restrições---------------------\n");
    print ("Digite o número de restrições: ");
    $number_Constraints = (int)readline();

    for ($i = 1; $i <= $number_Constraints; $i++) {

        print ("Para a " . $i . "th restrição\n");

        for ($j = 0; $j <= ($number_Decision + $number_Constraints); $j++) {

            if ($j == 0) {

                $matrix_Restrictions[$i][$j] = 0;

            }
            else {

                if ($j <= $number_Decision) {

                    print ("Digite o coeficiente da " . $j . "th variável de decisão: ");
                    $matrix_Restrictions[$i][$j] = (float)readline();

                } elseif ($j > $number_Decision) {

                    $matrix_Restrictions[$i][$j] = 0;

                }

                if ($i == $number_Constraints) {

                    $matrix_Restrictions[$i + 1][$j] = 0;

                }

            }

        }

        $matrix_Restrictions[$i][$i + $number_Decision] = 1;
        print ("Digite o valor da restrição: ");
        $matrix_Restrictions[$i][$number_Decision + $number_Constraints + 1] = (float) readline();

    }

    $matrix_Restrictions[$number_Constraints + 1][$number_Decision + $number_Constraints + 1] = 0;
    $matrix_Restrictions[$number_Constraints + 1][0] = 0;

    for ($i = 1; $i <= $number_Decision; $i++){

        $matrix_Restrictions[$number_Constraints + 1][$i] = $object_Function[$i];

    }

    print("\nSchedule: \n");
    $aux = $number_Decision + 1;

    for ($i = 1; $i <= $number_Constraints; $i++){

        $matrix_Restrictions[$i][0] = $aux;
        $aux++;

    }

    for ($i = 1; $i <= ($number_Constraints + 1); $i++){

        for ($j = 0; $j <= ($number_Constraints + $number_Decision + 1); $j++){

            echo "  " . $matrix_Restrictions[$i][$j];

        }

        print("\n");

    }

    Simplex_Maximize();

    function Simplex_Maximize(){

        global $number_Constraints, $number_Decision, $matrix_Restrictions, $less_position1,
               $less_position2, $column_pivot, $less, $pivot, $column_pivot1;

        for ($i = 1; $i <= $number_Constraints; $i++){// Percorre o tableau

            for ($j = 1; $j <= ($number_Constraints + $number_Decision + 1); $j++) {

                if ($j == $less_position1){ // Se j for igual a coluna pivô

                    $column_pivot[$i] = $matrix_Restrictions[$i][$number_Constraints + $number_Decision + 1] / $matrix_Restrictions[$i][$j]; // Divide o resultado da restrição pelo elemento da coluna pivô
                    $column_pivot1[$i] = $matrix_Restrictions[$i][$j];

                }

            }
        }

        $column_pivot1[$number_Constraints + 1] = $less;
        $less = 99999;

        for ($i = 1; $i <= $number_Constraints; $i++){ // Percorre os valores do resultado da divisão para descobrir quem é o pivô da iteração

            if ($i == 1 and $column_pivot[$i] >= 0){

                $less = $column_pivot[$i];
                $pivot = $matrix_Restrictions[$i][$less_position1];
                $less_position2 = $i;

            }
            else{

                if($column_pivot[$i] < $less and $column_pivot[$i] >= 0){

                    $less = $column_pivot[$i];
                    $pivot = $matrix_Restrictions[$i][$less_position1];
                    $less_position2 = $i;

                }

            }

        }

        for ($i = 1; $i <= $number_Constraints; $i++){ //Altera os valores da base no tableau

            if ($i == $less_position2) {

                $matrix_Restrictions[$i][0] = $less_position1;

            }

        }

        for ($i = 1; $i <= $number_Constraints; $i++){ // Percorre o tableau

            for ($j = 1; $j <= ($number_Constraints + $number_Decision + 1); $j++){ //atualiza os elementos da linha pivô fazendo a divisão desses elementos pelo pivô

                if ($less_position2 == $i){

                    $matrix_Restrictions[$i][$j] = $matrix_Restrictions[$i][$j] / $pivot;

                }

            }

        }

        for ($i = 1; $i <= ($number_Constraints + 1); $i++){

            for ($j = 1; $j <= ($number_Constraints + $number_Decision + 1); $j++){

                if ($less_position2 != $i){

                    $matrix_Restrictions[$i][$j] = $matrix_Restrictions[$i][$j] - ($column_pivot1[$i] * $matrix_Restrictions[$less_position2][$j]);

                }

            }

        }

        print("\n");

        for ($i = 1; $i <= ($number_Constraints + 1); $i++){

            for($j = 0; $j <= ($number_Constraints + $number_Decision + 1); $j++){

                echo $matrix_Restrictions[$i][$j] . " ";

            }

            print("\n");

        }

        for ($j = 1; $j <= ($number_Constraints + $number_Decision + 1); $j++){ // Verifica qual elemento da função objetivo é menor e grava sua posição

            if ($j == 1){ //atribui o primeiro elemento como menor

                $less = $matrix_Restrictions[$number_Constraints + 1][$j];

            }
            else{

                if ($matrix_Restrictions[$number_Constraints + 1][$j] < $less){

                    $less = $matrix_Restrictions[$number_Constraints + 1][$j];
                    $less_position1 = $j;

                }

            }

        }

        for ($j = 1; $j <= ($number_Constraints + $number_Decision + 1); $j++){ // verificação de parada do algoritmo

            if($matrix_Restrictions[$number_Constraints + 1][$j] < 0){

                Simplex_Maximize();

            }

        }

    }

    print("\n");

    for ($i = 1; $i <= ($number_Constraints + 1); $i++){

        $j = 0;
        if ($matrix_Restrictions[$i][$j] > 0 and $matrix_Restrictions[$i][$j] <=  $number_Decision ){

            echo $matrix_Restrictions[$i][$j] . "th variável de decisão = " . $matrix_Restrictions[$i][($number_Constraints + $number_Decision + 1)];
            print("\n");
        }
        if ($matrix_Restrictions[$i][$j] == 0){

            echo "Z = " . $matrix_Restrictions[$i][$number_Constraints + $number_Decision + 1];

        }

    }