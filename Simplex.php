<?php

/*
    Aluno: Humberto Pereira Teixeira Silva ------- Universidade Federal de Goiás - Regional Goiânia
    Professor: Cássio Vinhal
    Curso: Engenharia de Computação - Turma 25

    Obs: Este algoritmo Simplex é usado para maximizar problemas de programação linear.
         Funciona apenas para restrições maiores ou iguais a zero.
*/

    global $qtd_restricoes, $qtd_var_decisao, $matriz_restricoes, $less, $bigger, $bigger_position1,
           $less_position1, $less_position2, $column_pivot, $pivot, $pivot_line, $object_Function, $aux, $qtd_interacao;;


    print ("Digite o número de variáveis de decisão: ");
    $qtd_var_decisao = (int) trim(fgets(STDIN));
    print ("---------------------Para a Função Objetivo---------------------\n");

    for ($i = 1; $i <= $qtd_var_decisao; $i++) {

        print ("Digite o coeficiente da " . $i . "th variável de decisão: ");
        $object_Function[$i] = -(float)trim(fgets(STDIN));
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
    $qtd_restricoes = (int)trim(fgets(STDIN));

    for ($i = 1; $i <= $qtd_restricoes; $i++) {

        print ("Para a " . $i . "th restrição\n");

        for ($j = 0; $j <= ($qtd_var_decisao + $qtd_restricoes); $j++) {

            if ($j == 0) {

                $matriz_restricoes[$i][$j] = 0;

            }
            else {

                if ($j <= $qtd_var_decisao) {

                    print ("Digite o coeficiente da " . $j . "th variável de decisão: ");
                    $matriz_restricoes[$i][$j] = (float)trim(fgets(STDIN));

                } elseif ($j > $qtd_var_decisao) {

                    $matriz_restricoes[$i][$j] = 0;

                }

                if ($i == $qtd_restricoes) {

                    $matriz_restricoes[$i + 1][$j] = 0;

                }

            }

        }

        $matriz_restricoes[$i][$i + $qtd_var_decisao] = 1;
        print ("Digite o valor da restrição: ");
        $matriz_restricoes[$i][$qtd_var_decisao + $qtd_restricoes + 1] = (float) trim(fgets(STDIN));

    }

    $matriz_restricoes[$qtd_restricoes + 1][$qtd_var_decisao + $qtd_restricoes + 1] = 0;
    $matriz_restricoes[$qtd_restricoes + 1][0] = 0;

    for ($i = 1; $i <= $qtd_var_decisao; $i++){

        $matriz_restricoes[$qtd_restricoes + 1][$i] = $object_Function[$i];

    }

    print("\nTabela Original: \n");
    $aux = $qtd_var_decisao + 1;

    for ($i = 1; $i <= $qtd_restricoes; $i++){

        $matriz_restricoes[$i][0] = $aux;
        $aux++;

    }

    for ($i = 1; $i <= ($qtd_restricoes + 1); $i++){

        for ($j = 0; $j <= ($qtd_restricoes + $qtd_var_decisao + 1); $j++){

            echo "  " . round($matriz_restricoes[$i][$j], 2);

        }

        print("\n");

    }

    $qtd_interacao = 1;

    Simplex_Maximize($qtd_interacao);

    function Simplex_Maximize(int $qtd_interacao){

        global $qtd_restricoes, $qtd_var_decisao, $matriz_restricoes, $less_position1,
               $less_position2, $column_pivot, $less, $pivot, $column_pivot1;

        for ($i = 1; $i <= $qtd_restricoes; $i++){// Percorre o tableau

            for ($j = 1; $j <= ($qtd_restricoes + $qtd_var_decisao + 1); $j++) {

                if ($j == $less_position1){ // Se j for igual a coluna pivô

                    $column_pivot[$i] = $matriz_restricoes[$i][$qtd_restricoes + $qtd_var_decisao + 1] / $matriz_restricoes[$i][$j]; // Divide o resultado da restrição pelo elemento da coluna pivô
                    $column_pivot1[$i] = $matriz_restricoes[$i][$j];

                }

            }
        }

        $column_pivot1[$qtd_restricoes + 1] = $less;
        $less = 99999;

        for ($i = 1; $i <= $qtd_restricoes; $i++){ // Percorre os valores do resultado da divisão para descobrir quem é o pivô da iteração

            if ($i == 1 and $column_pivot[$i] >= 0){

                $less = $column_pivot[$i];
                $pivot = $matriz_restricoes[$i][$less_position1];
                $less_position2 = $i;

            }
            else{

                if($column_pivot[$i] < $less and $column_pivot[$i] >= 0){

                    $less = $column_pivot[$i];
                    $pivot = $matriz_restricoes[$i][$less_position1];
                    $less_position2 = $i;

                }

            }

        }

        for ($i = 1; $i <= $qtd_restricoes; $i++){ //Altera os valores da base no tableau

            if ($i == $less_position2) {

                $matriz_restricoes[$i][0] = $less_position1;

            }

        }

        for ($i = 1; $i <= $qtd_restricoes; $i++){ // Percorre o tableau

            for ($j = 1; $j <= ($qtd_restricoes + $qtd_var_decisao + 1); $j++){ //atualiza os elementos da linha pivô fazendo a divisão desses elementos pelo pivô

                if ($less_position2 == $i){

                    $matriz_restricoes[$i][$j] = $matriz_restricoes[$i][$j] / $pivot;

                }

            }

        }

        for ($i = 1; $i <= ($qtd_restricoes + 1); $i++){

            for ($j = 1; $j <= ($qtd_restricoes + $qtd_var_decisao + 1); $j++){

                if ($less_position2 != $i){

                    $matriz_restricoes[$i][$j] = $matriz_restricoes[$i][$j] - ($column_pivot1[$i] * $matriz_restricoes[$less_position2][$j]);

                }

            }

        }

        print("\n");

        print("\nTabela (Iteração $qtd_interacao): \n");

        for ($i = 1; $i <= ($qtd_restricoes + 1); $i++){

            for($j = 0; $j <= ($qtd_restricoes + $qtd_var_decisao + 1); $j++){

                echo " " . round($matriz_restricoes[$i][$j], 2) . " ";

            }

            print("\n");

        }

        for ($j = 1; $j <= ($qtd_restricoes + $qtd_var_decisao + 1); $j++){ // Verifica qual elemento da função objetivo é menor e grava sua posição

            if ($j == 1){ //atribui o primeiro elemento como menor

                $less = $matriz_restricoes[$qtd_restricoes + 1][$j];

            }
            else{

                if ($matriz_restricoes[$qtd_restricoes + 1][$j] < $less){

                    $less = $matriz_restricoes[$qtd_restricoes + 1][$j];
                    $less_position1 = $j;

                }

            }

        }

        for ($j = 1; $j <= ($qtd_restricoes + $qtd_var_decisao + 1); $j++){ // verificação de parada do algoritmo

            if($matriz_restricoes[$qtd_restricoes + 1][$j] < 0){

                Simplex_Maximize($qtd_interacao + 1);

            }

        }

    }

    print("\n");

    for ($i = 1; $i <= ($qtd_restricoes + 1); $i++){

        $j = 0;
        if ($matriz_restricoes[$i][$j] > 0 and $matriz_restricoes[$i][$j] <=  $qtd_var_decisao ){

            echo $matriz_restricoes[$i][$j] . "th variável de decisão = " . $matriz_restricoes[$i][($qtd_restricoes + $qtd_var_decisao + 1)];
            print("\n");
        }
        if ($matriz_restricoes[$i][$j] == 0){

            echo "Z = " . $matriz_restricoes[$i][$qtd_restricoes + $qtd_var_decisao + 1];

        }

    }