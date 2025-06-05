  <?php
        // Fetch students from the database
        $students = $con->getStudents();
        foreach ($students as $student) {



          ?>
          <tr>
            <td><?php echo $student['student_id'] ?></td>
            <td><?php echo $student['student_FN'] . ' ' . $student['student_LN']; ?></td>
            <td><?php echo $student['student_email'] ?></td>

            <td>
              <div class="btn-group" role="group">
                <form action="update_students.php" method="POST">
                  <input type="hidden" name="student_id" value="<?php echo $student ['student_id'] ?>">
                  <button type="submit" class="btn btn-sm btn-warning">Edit</button>

                </form>

              </div>
              <button class="btn btn-sm btn-danger">Delete</button>
            </td>
          </tr>
          <?php

        }

        ?>

        <tr>
          <td>1</td>
          <td>BS Information Technology</td>
          <td>
            <button class="btn btn-sm btn-warning">Edit</button>
            <button class="btn btn-sm btn-danger">Delete</button>
          </td>
        </tr>